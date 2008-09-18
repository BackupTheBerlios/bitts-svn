<?php
/****************************************************************************
 * CODE FILE   : dutch.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 september 2008
 * Description : Dutch language file
 */

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'nl_NL');

define('DATE_FORMAT_SHORT', '%d-%m-%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd-m-Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('NUMBER_DECIMAL_POINT', ',');
define('NUMBER_THOUSANDS_SEPARATOR', '.');

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
define('TITLE', COMPANY_NAME . ' - ' . PROJECT_NAME . ' ' . PROJECT_VERSION);

define('HEADER_TEXT_CURRENT_USER', 'Huidige gebruiker : ');
define('HEADER_TEXT_NO_CURRENT_USER', 'Niet ingelogd!');
define('HEADER_TEXT_YOUR_DATA', 'Uw gegevens');
define('HEADER_TEXT_LOGIN', 'Inloggen');
define('HEADER_TEXT_LOGOUT', 'Uitloggen');
define('HEADER_TEXT_TIMEREGISTRATION', 'Tijdregistratie');
define('HEADER_TEXT_ANALYSIS', 'Rapportage');
define('HEADER_TEXT_ADMINISTRATION', 'Beheer');

define('BOX_HEADING_MAINMENU', 'Menu');
define('BOX_MAINMENU_HOME', 'Home');
define('BOX_MAINMENU_TIMEREGISTRATION', 'Tijdregistratie');
define('BOX_MAINMENU_ANALYSIS', 'Rapportage');
define('BOX_MAINMENU_ADMINISTRATION', 'Beheer');
define('BOX_MAINMENU_ADMINISTRATION_PROJECTS', 'Beheer projecten');
define('BOX_MAINMENU_ADMINISTRATION_CUSTOMERS', 'Beheer klanten');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES', 'Beheer medewerkers');

define('BODY_TEXT_LOGIN', 'Login');
define('BODY_TEXT_PASSWORD', 'Wachtwoord');
define('BODY_TEXT_PASSWORD_VERIFY', 'Herhaal wachtwoord');
define('BODY_TEXT_FULLNAME', 'Volledige naam');
define('BODY_TEXT_EMPLOYEE_ID', 'Personeelsnummer');
define('BODY_TEXT_IS_USER', 'Is gebruiker');
define('BODY_TEXT_IS_ANALYST', 'Is rapporteur');
define('BODY_TEXT_IS_ADMINISTRATOR', 'Is beheerder');
define('BODY_TEXT_YES', 'Ja');
define('BODY_TEXT_NO', 'Nee');
$LOGIN_ERROR_LEVEL = array (0 => '',
                            1 => 'Inloggen mislukt',
                            2 => 'Wachtwoorden komen niet overeen',
                            3 => 'Het wachtwoord mag niet leeg zijn');

define('TEXT_TIMEREGISTRATION_BACK', 'Periode terug');
define('TEXT_TIMEREGISTRATION_PERIOD', 'Periode : ');
define('TEXT_TIMEREGISTRATION_FORWARD', 'Periode vooruit');
define('TEXT_TIMEREGISTRATION_LOCKED', 'Deze periode is bevestigd en kan niet meer worden gewijzigd');
define('TEXT_TIMEREGISTRATION_IS_EMPTY', 'Geen activiteiten aanwezig');
define('TEXT_TIMEREGISTRATION_CONFIRM', 'Bevestig deze periode. LET OP: Hierna zijn wijzigingen niet meer mogelijk!');
define('TEXT_TIMEREGISTRATION_CONFIRM_QUESTION', 'Wilt u deze periode bevestigen?');
define('TEXT_TIMEREGISTRATION_CONFIRM_OK', 'Bevestigen OK');
define('TEXT_TIMEREGISTRATION_CONFIRM_CANCEL', 'Bevestigen annuleren');

define('TEXT_ACTIVITY_DAY', 'Dag');
define('TEXT_ACTIVITY_PROJECTNAME', 'Project');
define('TEXT_ACTIVITY_ROLENAME', 'Rol');
define('TEXT_ACTIVITY_AMOUNT', 'Aantal');
define('TEXT_ACTIVITY_UNIT', 'Eenheid');
define('TEXT_ACTIVITY_TRAVELDISTANCE', 'Reisafstand (km)');
define('TEXT_ACTIVITY_EXPENSES', 'Onkosten (&euro;)');
define('TEXT_ACTIVITY_TICKETNUMBER', 'Ticket nr.');
define('TEXT_ACTIVITY_COMMENT', 'Opmerkingen');
define('TEXT_ACTIVITY_EDIT', 'Wijzigen');
define('TEXT_ACTIVITY_DELETE', 'Verwijderen');
define('TEXT_ACTIVITY_DELETE_QUESTION', 'Wilt u bovenstaande activiteit verwijderen?');
define('TEXT_ACTIVITY_DELETE_OK', 'Verwijderen OK');
define('TEXT_ACTIVITY_DELETE_CANCEL', 'Verwijderen annuleren');
$ACTIVITY_ERROR_LEVEL = array (0 => '',
                               1 => 'Geen project gekozen',
                               2 => 'Geen rol gekozen',
                               3 => 'Aantal onjuist',
                               4 => 'Geen eenheid gekozen',
                               5 => 'Reisafstand onjuist',
                               6 => 'Onkosten onjuist',
                               7 => 'Ticket nr. is verplicht');

define('TEXT_CALENDAR_MONDAY', 'm');
define('TEXT_CALENDAR_TUESDAY', 'd');
define('TEXT_CALENDAR_WEDNESDAY', 'w');
define('TEXT_CALENDAR_THURSDAY', 'd');
define('TEXT_CALENDAR_FRIDAY', 'v');
define('TEXT_CALENDAR_SATURDAY', 'z');
define('TEXT_CALENDAR_SUNDAY', 'z');

define('TEXT_ACTIVITY_ENTRY_SELECTED_DATE', 'Geselecteerde datum: ');
define('TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED', 'Geen datum geselecteerd');
define('TEXT_ACTIVITY_ENTRY_SELECT', 'Selecteer');
define('TEXT_ACTIVITY_ENTRY_SAVE', 'Opslaan');
define('TEXT_ACTIVITY_ENTRY_CANCEL', 'Annuleren');

define('TEXT_ANALYSIS_BACK', 'Periode terug');
define('TEXT_ANALYSIS_PERIOD', 'Periode : ');
define('TEXT_ANALYSIS_FORWARD', 'Periode vooruit');

define('REPORT_NAME_EMPLOYEES', 'Overzicht medewerkers');
define('REPORT_NAME_PROJECTS', 'Overzicht projecten');
define('REPORT_NAME_TIMESHEETS', 'Overzicht urenstaten');
define('REPORT_EMPLOYEES_ID', 'nr');
define('REPORT_EMPLOYEES_FULLNAME', 'Naam');
define('REPORT_EMPLOYEES_IS_USER', 'Gebruiker');
define('REPORT_EMPLOYEES_IS_ANALYST', 'Analist');
define('REPORT_EMPLOYEES_IS_ADMINISTRATOR', 'Admin');
define('REPORT_EMPLOYEES_TIMESHEET_AVAILABLE', 'Timesheet');
define('REPORT_EMPLOYEES_TIMESHEET_LOCKED', 'Bevestigd');

define('REPORT_TEXT_CUSTOMER_NAME', 'Klant:');
define('REPORT_TEXT_DATE', 'Datum:');
define('REPORT_TEXT_PERIOD', 'Periode:');
define('REPORT_TEXT_PROJECT_NAME', 'Project:');
define('REPORT_TEXT_ROLE_NAME', 'Rol:');
define('REPORT_TEXT_EMPLOYEE_NAME', 'Medewerker:');
define('REPORT_TEXT_FOOTER_SIGNATURE_EMPLOYEE', 'Handtekening medewerker');
define('REPORT_TEXT_FOOTER_SIGNATURE_CUSTOMER', 'Handtekening klant');
define('REPORT_TEXT_FOOTER_ACKNOWLEDGE', 'Door ondertekening van deze urenstaat verklaart de opdrachtgever zich akkoord met de gespecificeerde aantallen, eenheden, kilometers en onkosten.');

define('REPORT_CHECKBOX_SHOW_TIMESHEET_INFO', 'Toon timesheet info');
define('REPORT_CHECKBOX_SHOW_ALL_EMPLOYEES', 'Toon alle (ex-)medewerkers');
define('REPORT_CHECKBOX_PER_EMPLOYEE', 'Per medewerker');
define('REPORT_CHECKBOX_SHOW_TARIFF', 'Toon tarieven');
define('REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE', 'Toon reisafstanden');
define('REPORT_CHECKBOX_SHOW_EXPENSES', 'Toon onkosten');
define('REPORT_CHECKBOX_SHOW_COMMENTS', 'Toon opmerkingen');

define('REPORT_TABLE_HEADER_DATE', 'Datum');
define('REPORT_TABLE_HEADER_EMPLOYEE_NAME', 'Medewerker');
define('REPORT_TABLE_HEADER_ACTIVITY_AMOUNT', 'Aantal');
define('REPORT_TABLE_HEADER_IS_TARIFF', ' a ');
define('REPORT_TABLE_HEADER_TRAVEL_DISTANCE', 'km');
define('REPORT_TABLE_HEADER_EXPENSES', 'Onkosten');
define('REPORT_TABLE_HEADER_TICKET_NUMBER', 'Ticket nr');
define('REPORT_TABLE_HEADER_TOTAL', 'Totaal');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . COMPANY_NAME . '</a>');
?>