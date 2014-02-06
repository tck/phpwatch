<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');

    class EmailChannel extends Channel
    {
        public function getSubjectFormat()
        {
            return $this->config['subject'];
        }

        public function getMessageFormat()
        {
            return $this->config['message'];
        }

        private function getSubject($monitor)
        {
            return sprintf($this->config['subject'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        private function getMessage($monitor)
        {
            $header = '';
            if (isset($GLOBALS['PW2_CONFIG']['email']['message_header']) && $GLOBALS['PW2_CONFIG']['email']['message_header']) {
                $header .= $GLOBALS['PW2_CONFIG']['email']['message_header'] . "\r\n";
            }
            $footer = '';
            if (isset($GLOBALS['PW2_CONFIG']['email']['message_footer']) && $GLOBALS['PW2_CONFIG']['email']['message_footer']) {
                $footer .= "\r\n" . $GLOBALS['PW2_CONFIG']['email']['message_footer'];
            }
            
            return $header . sprintf($this->config['message'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias()) . $footer;
        }

        public function getAddress()
        {
            return $this->config['address'];
        }

        public function doNotify($monitor)
        {
            $headers = '';
            if (isset($GLOBALS['PW2_CONFIG']['email']['from']) && $GLOBALS['PW2_CONFIG']['email']['from']) {
                $headers .= 'From: ' . $GLOBALS['PW2_CONFIG']['email']['from'] . "\r\n";
            }
            $headers .= 'X-Mailer: phpWatch' . "\r\n";
        
            mail($this->config['address'], $this->getSubject($monitor), $this->getMessage($monitor), $headers);
        }

        public function getName()
        {
            return 'Email Channel';
        }

        public function getDescription()
        {
            return 'Sends an e-mail to notify of service outages.';
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(strlen($data['subject']) == 0)
                $errors['subject'] = 'Subject cannot be blank.';
            $this->config['subject'] = $data['subject'];

            if(strlen($data['message']) == 0)
                $errors['message'] = 'Message cannot be blank.';
            $this->config['message'] = $data['message'];

            if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $data['address']))
                $errors['address'] = 'E-mail address is invalid.';
            $this->config['address'] = $data['address'];

            return $errors;
        }

        public function customProcessDelete()
        {
        }
        
        public function __toString()
        {
            return $this->config['address'];
        }
    }
?>
