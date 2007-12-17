<?php
/****************************************************************************
 * CLASS FILE  : language.php
 * Project     : BitTS - BART it TimeSheet
 * Auteur(s)   : Erwin Beukhof
 * Datum       : 17 december 2007
 * Beschrijving: browser language detection logic Copyright phpMyAdmin (select_lang.lib.php3 v1.24 04/19/2002)
 *               Copyright Stephane Garin <sgarin@sgarin.com> (detect_language.php v0.1 04/02/2002)
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  class language {
    public $languages, $catalog_languages, $browser_languages, $language;

    public function __construct($lng = '') {
      $this->languages = array('ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
                               'bg' => 'bg|bulgarian',
                               'br' => 'pt[-_]br|brazilian portuguese',
                               'ca' => 'ca|catalan',
                               'cs' => 'cs|czech',
                               'da' => 'da|danish',
                               'de' => 'de([-_][[:alpha:]]{2})?|german',
                               'el' => 'el|greek',
                               'en' => 'en([-_][[:alpha:]]{2})?|english',
                               'es' => 'es([-_][[:alpha:]]{2})?|spanish',
                               'et' => 'et|estonian',
                               'fi' => 'fi|finnish',
                               'fr' => 'fr([-_][[:alpha:]]{2})?|french',
                               'gl' => 'gl|galician',
                               'he' => 'he|hebrew',
                               'hu' => 'hu|hungarian',
                               'id' => 'id|indonesian',
                               'it' => 'it|italian',
                               'ja' => 'ja|japanese',
                               'ko' => 'ko|korean',
                               'ka' => 'ka|georgian',
                               'lt' => 'lt|lithuanian',
                               'lv' => 'lv|latvian',
                               'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
                               'no' => 'no|norwegian',
                               'pl' => 'pl|polish',
                               'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
                               'ro' => 'ro|romanian',
                               'ru' => 'ru|russian',
                               'sk' => 'sk|slovak',
                               'sr' => 'sr|serbian',
                               'sv' => 'sv|swedish',
                               'th' => 'th|thai',
                               'tr' => 'tr|turkish',
                               'uk' => 'uk|ukrainian',
                               'tw' => 'zh[-_]tw|chinese traditional',
                               'zh' => 'zh|chinese simplified');

      $this->catalog_languages = array();
      $database = $_SESSION['database'];
      $languages_query = $database->query("select languages_id, languages_name, languages_code, languages_image, languages_directory from " . TABLE_LANGUAGES);
      while ($languages_result = $database->fetch_array($languages_query)) {
        $this->catalog_languages[$languages_result['languages_code']] = array('id' => $languages_result['languages_id'],
                                                             'name' => $languages_result['languages_name'],
                                                             'image' => $languages_result['languages_image'],
                                                             'directory' => $languages_result['languages_directory']);
      }

      $this->browser_languages = '';
      $this->language = '';

      $this->set_language($lng);
    }

    function set_language($language) {
      if ( (tep_not_null($language)) && (isset($this->catalog_languages[$language])) ) {
        $this->language = $this->catalog_languages[$language];
      } else {
        $this->language = $this->catalog_languages[DEFAULT_LANGUAGE];
      }
    }

    function get_browser_language() {
      $this->browser_languages = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));

      for ($i=0, $n=sizeof($this->browser_languages); $i<$n; $i++) {
        reset($this->languages);
        while (list($key, $value) = each($this->languages)) {
          if (eregi('^(' . $value . ')(;q=[0-9]\\.[0-9])?$', $this->browser_languages[$i]) && isset($this->catalog_languages[$key])) {
          	$this->language = $this->catalog_languages[$key];
            break 2;
          }
        }
      }
    }
  }
?>