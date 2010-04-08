<?php
    class FormHelpers
    {
        private static function optional($string)
        {
            if($string === null)
                return '';
            return ' ' . $string;
        }

        public static function donePOST()
        {
            return sizeof($_POST) > 0;
        }

        public static function checkError($key, $errors)
        {
            if(array_key_exists($key, $errors))
                p($errors[$key]);
        }

        public static function startForm($method, $action)
        {
            p('<form method="' . $method . '" action="' . $action . '"' . FormHelpers::optional($attribs) . ' />');
        }

        public static function createText($name, $value, $attribs = null)
        {
            p('<input type="text" name="' . $name . '" value="' . $value . '"' . FormHelpers::optional($attribs) . ' />');
        }

        public static function createTextarea($name, $value, $attribs = null)
        {
            p('<textarea name="' . $name . '"' . FormHelpers::optional($attribs) . '>' . $value . '</textarea>');
        }

        public static function getOption($display, $value = null, $attribs = null)
        {
            return '<option' . FormHelpers::optional($value) . FormHelpers::optional($attribs) . '>' . $display .
            '</option>';
        }

        public static function createSelect($name, $options, $attribs = null)
        {
            p('<select name="' . $name . '"' . FormHelpers::optional($attribs) . '>');
            foreach($options as $o)
                p($o);
            p('</select>');
        }

        public static function createSubmit($value)
        {
            p('<input type="submit" value="' . $value . '" />');
        }

        public static function endForm()
        {
            p('</form>');
        }
    }
?>