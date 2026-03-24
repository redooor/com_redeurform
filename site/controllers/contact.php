<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Captcha\Captcha;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class RedeuformControllerContact extends BaseController
{
    /**
     * Method to handle the form submission
     */
    public function submit()
    {
        // 1. Check for the CSRF token for security
        if (!Session::checkToken()) {
            $this->setRedirect(Route::_('index.php?option=com_redeuform'), 'Invalid Token', 'error');
            return false;
        }

        $app   = Factory::getApplication();

        // Check if Captcha is enabled globally
        $captchaPlugin = Factory::getConfig()->get('captcha');

        if ($captchaPlugin != '0') {
            $captcha = Captcha::getInstance($captchaPlugin);

            // Validate the user's response
            // In Joomla 3, 'captcha' is the field ID used in the display() method above
            if (!$captcha->checkAnswer('captcha')) {
                $app->enqueueMessage(Text::_('JERROR_CAPTCHA_FAILED'), 'error');
                $this->setRedirect(Route::_('index.php?option=com_redeuform&view=contact', false));
                return false;
            }
        }

        $input = $app->input;

        // 2. Get user input from the form
        $name    = $input->getString('name');
        $email   = $input->getString('email');
        $phone   = mb_substr($input->getString('phone'), 0, 40); // limit up to 40 characters
        $message = mb_substr($input->getString('message'), 0, 50); // limit to 50
        $data = [
            '[name]'    => $name,
            '[email]'   => $email,
            '[phone]'   => $phone,
            '[message]' => $message
        ];

        // Server-side Regex: Allows digits, spaces, dashes, parentheses, and a leading plus
        $phoneRegex = '/^[\d\s\-+\(\)]+$/';

        if (!empty($phone) && !preg_match($phoneRegex, $phone)) {
            Factory::getApplication()->enqueueMessage(Text::_('COM_REDEUFORM_PHONE_INVALID'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_redeuform&view=contact', false));
            return false;
        }

        // 3. Retrieve admin settings (defined in your manifest.xml)
        $params         = ComponentHelper::getParams('com_redeuform');
        $receivingEmail = $params->get('receiving_email');
        $sendAsEmail    = $params->get('send_as_email');
        $emailTemplate  = $params->get('email_template');

        // 4. Prepare the email body using the template placeholders
        $body = str_replace(array_keys($data), array_values($data), $emailTemplate);

        // 5. Use Joomla's Mailer to send the email
        $mailer = Factory::getMailer();

        // Set the sender (Send-As Email)
        $sender = array($sendAsEmail, $app->get('sitename'));
        $mailer->setSender($sender);

        // Set the recipient (Receiving Email)
        $mailer->addRecipient($receivingEmail);

        $mailer->setSubject('New Contact Form Submission');
        $mailer->setBody($body);
        $mailer->isHtml(false); // Set to true if your template contains HTML

        if ($mailer->Send() === true) {
            $app->enqueueMessage('Message sent successfully!');

            // 2. Log to Database
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $columns = array('name', 'email', 'phone', 'message', 'created');
            $values = array(
                $db->quote($name),
                $db->quote($email),
                $db->quote($phone),
                $db->quote($message),
                $db->quote(Factory::getDate()->toSql())
            );

            $query->insert($db->quoteName('#__redeuform_messages'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));

            $db->setQuery($query);
            $db->execute();

            // Success: Redirect to the 'thanks' view
            $this->setRedirect(
                Route::_('index.php?option=com_redeuform&view=thanks', false),
                Text::_('COM_REDEUFORM_MSG_SUCCESS')
            );
        } else {
            $app->enqueueMessage('Error sending email.', 'error');

            // Error: Redirect back to the form with an error message
            $this->setRedirect(
                Route::_('index.php?option=com_redeuform&view=contact', false),
                Text::_('COM_REDEUFORM_MSG_ERROR'),
                'error'
            );
        }
    }
}
