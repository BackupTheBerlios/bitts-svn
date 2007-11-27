<?php
/****************************************************************************
 * CODE FILE   : dutch.php
 * Project     : BitTS - BART it TimeSheet
 * Auteur(s)   : Erwin Beukhof
 * Datum       : 26 november 2007
 * Beschrijving: Nederlands taalbestand
 */

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'nl_NL.ISO8859-1');

define('DATE_FORMAT_SHORT', '%d-%m-%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd-m-Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

$LANGUAGE_ARRAY_MONTH = array ("January" => "januari", "February" => "februari", "March" => "maart", "April" => "april", "May" => "mei", "June" => "juni", "July" => "juli", "August" => "augustus", "September" => "september", "October" => "oktober", "November" => "november", "December" => "december");

$LANGUAGE_ARRAY_DAY = array ("Monday" => "maandag", "Tuesday" => "dinsdag", "Wednesday" => "woensdag", "Thursday" => "donderdag", "Friday" => "vrijdag", "Saturday" => "zaterdag", "Sunday" => "zondag");

////
// Return date in raw format
// $date should be in format dd-mm-yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Account aanmaken');
define('HEADER_TITLE_MY_ACCOUNT', 'Mijn account');
define('HEADER_TITLE_CART_CONTENTS', 'Inhoud winkelwagen');
define('HEADER_TITLE_CHECKOUT', 'Afrekenen');
define('HEADER_TITLE_TOP', 'Hoofdpagina');
define('HEADER_TITLE_CATALOG', 'Winkel');
define('HEADER_TITLE_LOGOFF', 'Uitloggen');
define('HEADER_TITLE_LOGIN', 'Inloggen');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'pagina\'s bekeken sinds');

// text for gender
define('MALE', 'Man');
define('FEMALE', 'Vrouw');
define('MALE_ADDRESS', 'Dhr.');
define('FEMALE_ADDRESS', 'Mevr.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd-mm-yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Categorie&euml;n');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Fabrikanten');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Nieuwe producten');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Snel zoeken');
define('BOX_SEARCH_TEXT', 'Gebruik sleutelwoorden om het product te vinden.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Geavanceerd zoeken');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Aanbiedingen');

define('CATEGORY_COMPANY', 'Bedrijfsgegevens');
define('CATEGORY_PERSONAL', 'Uw persoonlijke gegevens');
define('CATEGORY_ADDRESS', 'Uw adres');
define('CATEGORY_CONTACT', 'Uw contact informatie');
define('CATEGORY_OPTIONS', 'Opties');
define('CATEGORY_PASSWORD', 'Uw wachtwoord');

define('ENTRY_COMPANY', 'Bedrijfsnaam:');
// Gewijzigde code tbv TVA_INTRACOM_v3.0
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
// Einde gewijzigde code tbv TVA_INTRACOM_v3.0

// Nieuwe code tbv TVA_INTRACOM_v3.0
// define('ENTRY_COMPANY_ERROR', 'De Bedrijfsnaam dient minimaal ' . ENTRY_COMPANY_MIN_LENGTH . ' karakters te bevatten.');
// define('ENTRY_COMPANY_TEXT', 'Alleen indien u het Bedrijf gefactureerd wilt hebben voor uw order');
// Einde nieuwe code tbv TVA_INTRACOM_v3.0

// Ajout pour TVA_INTRACOM_v3.0
define('ENTRY_TVA_INTRACOM', 'Intracomm. BTW Nr :');
define('ENTRY_TVA_INTRACOM_ERROR', 'Het intracomm. BTW nummer van uw Bedrijf dient minimaal ' . ENTRY_TVA_INTRACOM_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_TVA_INTRACOM_TEXT', 'Alleen indien het ingevulde Bedrijf zich binnen de EEG bevindt');
define('ENTRY_CONTROL_TVA_INTRACOM', 'Na controle blijkt dat uw intracomm. BTW nummer niet correct is. Leave it blank if you don\'t know it.<br>For info, it must be structured like this:<br>Germany: \'DE\' + 9 numeric characters<br>Austria: \'AT\' + 9 numeric and alphanumeric characters<br>Belgium : \'BE\' + 9 numeric characters<br>Denmark : \'DK\' + 8 numeric characters<br>Spain: \'ES\' + 9 characters<br>Finland : \'FI\' + 8 numeric characters<br>France: \'FR\' + 2 figures (informatic key) + N° SIREN (9 figures)<br>United Kingdom: \'GB\' + 9 numeric characters<br>Greece: \'EL\' + 9 numeric characters<br>Irlande : \'IE\' + 8 numeric and alphabetic characters<br>Italy : \'IT\' + 11 numeric characters<br>Luxembourg: \'LU\' + 8 numeric characters<br>Netherlands: \'NL\' + 12 alphanumeric characters, one of them a letter<br>Portugal : \'PT\' + 9 numeric characters<br>Sweden : \'SE\' + 12 numeric characters<br>Cyprus : \'CY\' + 8 numeric characters and 1 alphabetic character<br>Estonia : \'EE\' + 9 numeric characters<br>Hungary : \'HU\' + 8 numeric characters<br>Latvia : \'LV\' + 11 numeric characters<br>Lithuania : \'LT\' + 9 or 12 numeric characters<br>Malta : \'MT\' + 8 numeric characters<br>Poland : \'PL\' + 10 numeric characters<br>Slovakia : \'SK\' + 9 or 10 numeric characters<br>Czech Republic : \'CZ\' + 8 or 9 or 10 numeric characters<br>Slovania : \'SI\' + 8 numeric characters');
define('ENTRY_NO_VERIF_TVA_INTRACOM', 'Impossible to check your VAT number: leave it empty');
  // Begin update for TVA_INTRACOM_v3.9
        define('ENTRY_CONTROL_TVA_INTRACOM_COUNTRY', 'The intracom. VAT number does not correspond to the indicated country. Leave it blank if you don\'t know it.');
        // End update for TVA_INTRACOM_v3.9
// Fin ajout pour TVA_INTRACOM_v3.0

define('ENTRY_GENDER', 'Geslacht:');
define('ENTRY_GENDER_ERROR', 'Selecteer a.u.b. uw geslacht.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Voornaam:');
define('ENTRY_FIRST_NAME_ERROR', 'Uw voornaam dient minimaal ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Achternaam:');
define('ENTRY_LAST_NAME_ERROR', 'Uw achternaam dient minimaal ' . ENTRY_LAST_NAME_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Uw geboortedatum dient het volgende format te hebben: DD-MM-JJJJ (bijv. 21-05-1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (bijv. 21-05-1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mailadres:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Uw e-mailadres dient minimaal ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Uw e-mailadres blijkt ongeldig - pas a.u.b. waar nodig aan.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Uw e-mailadres is reeds bekend in onze bestanden - log a.u.b. in met het e-mailadres of maak een account aan met een ander adres.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Straat &amp; nr.:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Uw straatnaam &amp; nummer dienen minimaal ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Wijk:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_POST_CODE_ERROR', 'Uw postcode dient minimaal ' . ENTRY_POSTCODE_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Plaats:');
define('ENTRY_CITY_ERROR', 'Uw plaatsnaam dient minimaal ' . ENTRY_CITY_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Provincie:');
define('ENTRY_STATE_ERROR', 'Uw provincie dient minimaal ' . ENTRY_STATE_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_STATE_ERROR_SELECT', 'Selecteer a.u.b. een provincie uit het provincies pull down menu.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', 'U dient een land te selecteren uit het landen pull down menu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telefoonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Uw telefoonnummer dient minimaal ' . ENTRY_TELEPHONE_MIN_LENGTH . ' karakters te bevatten');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Geabonneerd');
define('ENTRY_NEWSLETTER_NO', 'Niet geabonneerd');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Wachtwoord:');
define('ENTRY_PASSWORD_ERROR', 'Uw wachtwoord dient minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'De wachtwoord bevestiging dient identiek te zijn aan uw wachtwoord.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Wachtwoord bevestiging:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Huidig wachtwoord:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Uw wachtwoord dient minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_PASSWORD_NEW', 'Nieuw wachtwoord:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Uw nieuwe wachtwoord dient minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters te bevatten.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'De wachtwoord bevestiging dient identiek te zijn aan uw nieuwe wachtwoord.');
define('PASSWORD_HIDDEN', '--VERBORGEN--');

define('FORM_REQUIRED_INFORMATION', '* Verplicht veld');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Resultaat pagina\'s:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Product <b>%d</b> t/m <b>%d</b> (van <b>%d</b> producten)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Order <b>%d</b> t/m <b>%d</b> (van <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Recensie <b>%d</b> t/m <b>%d</b> (van <b>%d</b> recensies)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Nieuw product <b>%d</b> t/m <b>%d</b> (van <b>%d</b> nieuwe producten)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Aanbieding <b>%d</b> t/m <b>%d</b> (van <b>%d</b> aanbiedingen)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Eerste pagina');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Vorige pagina');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Volgende pagina');
define('PREVNEXT_TITLE_LAST_PAGE', 'Laatste pagina');
define('PREVNEXT_TITLE_PAGE_NO', 'Pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Vorige set van %d pagina\'s');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Volgende set van %d pagina\'s');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;EERSTE');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Vorige]');
define('PREVNEXT_BUTTON_NEXT', '[Volgende&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAATSTE&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Adres toevoegen');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adresboek');
define('IMAGE_BUTTON_BACK', 'Terug');
define('IMAGE_BUTTON_BUY_NOW', 'Koop nu');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adres wijzigen');
define('IMAGE_BUTTON_CHECKOUT', 'Afrekenen');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bevestig order');
define('IMAGE_BUTTON_CONTINUE', 'Ga verder');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Winkel verder');
define('IMAGE_BUTTON_DELETE', 'Verwijderen');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Wijzig account');
define('IMAGE_BUTTON_HISTORY', 'Orderhistorie');
define('IMAGE_BUTTON_LOGIN', 'Log in');
define('IMAGE_BUTTON_IN_CART', 'Voeg toe aan winkelwagen');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Meldingen');
define('IMAGE_BUTTON_QUICK_FIND', 'Snel zoeken');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Verwijder meldingen');
define('IMAGE_BUTTON_REVIEWS', 'Recensies');
define('IMAGE_BUTTON_SEARCH', 'Zoeken');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Verzendopties');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Vertel een vriend');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update winkelwagen');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Schrijf recensie');

define('SMALL_IMAGE_BUTTON_DELETE', 'Verwijderen');
define('SMALL_IMAGE_BUTTON_EDIT', 'Wijzigen');
define('SMALL_IMAGE_BUTTON_VIEW', 'Tonen');

define('ICON_ARROW_RIGHT', 'meer');
define('ICON_CART', 'In winkelwagen');
define('ICON_ERROR', 'Fout');
define('ICON_SUCCESS', 'Succes');
define('ICON_WARNING', 'Waarschuwing');

define('TEXT_GREETING_PERSONAL', 'Welkom terug <span class="greetUser">%s!</span> Wilt u zien welke <a href="%s"><u>nieuwe producten</u></a> beschikbaar zijn?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Indien u niet %s bent, <a href="%s"><u>log uzelf in</u></a> met uw account gegevens.</small>');
//define('TEXT_GREETING_GUEST', 'Welkom <span class="greetUser">gast!</span> Wilt u <a href="%s"><u>inloggen</u></a>? Of wilt u een nieuw account <a href="%s"><u>aanmaken</u></a>?');
define('TEXT_GREETING_GUEST', 'Van harte welkom in de winkel van ' . STORE_NAME . '. Kijkt u rustig rond, u kunt op elk gewenst moment <a href="%s"><u>inloggen</u></a> of, geheel vrijblijvend, een nieuw account <a href="%s"><u>aanmaken</u></a>.');

define('TEXT_SORT_PRODUCTS', 'Sorteer producten ');
define('TEXT_DESCENDINGLY', 'aflopend');
define('TEXT_ASCENDINGLY', 'oplopend');
define('TEXT_BY', ' met ');

define('TEXT_REVIEW_BY', 'door %s');
define('TEXT_REVIEW_WORD_COUNT', '%s woorden');
define('TEXT_REVIEW_RATING', 'Waardering: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Datum toegevoegd: %s');
define('TEXT_NO_REVIEWS', 'Er zijn op dit moment geen product recensies.');

define('TEXT_NO_NEW_PRODUCTS', 'Er zijn op dit moment geen nieuwe producten.');

define('TEXT_UNKNOWN_TAX_RATE', 'Onbekend belastingtarief');

define('TEXT_REQUIRED', '<span class="errorText">Verplicht</span>');

define('TEXT_TAX_POLICY', 'incl. BTW');

define('FOOTER_TEXT_BODY', 'Alle prijzen in EURO ' . TEXT_TAX_POLICY . '<br>---<br>Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>, K.v.K. Utrecht: 30211123, BTW nr: NL 170480859B01');
?>
