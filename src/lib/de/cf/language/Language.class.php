<?php
/**
 * Class that handles the language operation
 *
 * @author Manuel Schäfer
 */
class Language {

        public function __construct() {
            sfLoader::loadHelpers('I18N');
	}

        /**
         * Function extracts all language files from app Dir.
         *
         * @param array $languages
         * @return array $result
         */
	public function extractLanguages(array $languages) {
            $dir = array();
            $language = array();
            $dir = array_diff(scandir(sfConfig::get('sf_app_i18n_dir')), Array( ".", ".." ));
            
            foreach($dir AS $item) {
                $language = explode('_', $item);
                $result[] = $item;
                $result[] = $language[0];
                unset($language);
            }
            return $result;
        }

        /**
         * function creates out of the language array, an resultset
         * for extjs view.
         *
         * @param array $languages
         * @return array $result, with value de_DE and Text German
         */
        public function buildLanguages(array $languages){
            $result = array();
            $b=0;
            $c=0;
            for($a=0;$a<count($languages)/2;$a++) {
                $result[$c]['value'] = $languages[$b++];
                $result[$c++]['text'] = format_language($languages[$b++]);
            }
            return $result;
	}

        /**
         *
         * Function that returns the defualt language
         *
         * @param String $language, e.g. en_US, de_DE
         * @return String, formated in correct language, e.g. English, German
         */
        public static function buildDefaultLanguage($language) {
            sfLoader::loadHelpers('I18N');
            $result = array();
            $result = explode('_', $language);
            return format_language($result[0]);
        }


        /**
         * Function loads all translations for the textfields and combobox
         *
         * @param user $conext
         * @return array $result
         */
        public function loadAjaxLanguage($conext) {
            $result = array();
            $result['login'] = $conext->getI18N()->__('Login',null,'login');
            $result['username'] = $conext->getI18N()->__('Username',null,'login');
            $result['password'] = $conext->getI18N()->__('Password',null,'login');
            $result['language'] = $conext->getI18N()->__('Language',null,'login');
            $result['close'] = $conext->getI18N()->__('Close',null,'login');
            return $result;
        }

}
?>