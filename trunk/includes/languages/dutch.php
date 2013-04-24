<?php
/****************************************************************************
 * CODE FILE   : dutch.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 24 april 2013
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

define('HEADER_INFO_UNCONFIRMED_PERIOD', 'De urenstaat voor periode %s is nog niet bevestigd');

define('HEADER_TEXT_CURRENT_USER', 'Huidige gebruiker : ');
define('HEADER_TEXT_NO_CURRENT_USER', 'Niet ingelogd!');
define('HEADER_TEXT_YOUR_DATA', 'Uw gegevens');
define('HEADER_TEXT_CURRENT_PROJECTS', 'Lopende projecten');
define('HEADER_TEXT_LOGIN', 'Inloggen');
define('HEADER_TEXT_LOGOUT', 'Uitloggen');
define('HEADER_TEXT_TIMEREGISTRATION', 'Tijdregistratie');
define('HEADER_TEXT_TIMEREGISTRATION_PUNCH_CLOCK', 'Prikklok');
define('HEADER_TEXT_TIMEREGISTRATION_CALENDAR', 'Kalender');
define('HEADER_TEXT_ANALYSIS', 'Rapportage');
define('HEADER_TEXT_ADMINISTRATION', 'Beheer');
define('HEADER_TEXT_ADMINISTRATION_CUSTOMERS', 'Beheer Debiteuren');
define('HEADER_TEXT_ADMINISTRATION_BUSINESS_UNITS', 'Beheer Business Units');
define('HEADER_TEXT_ADMINISTRATION_PROJECTS', 'Beheer Projecten');
define('HEADER_TEXT_ADMINISTRATION_CATEGORIES', 'Beheer Categorie&euml;n');
define('HEADER_TEXT_ADMINISTRATION_ROLES', 'Beheer Rollen');
define('HEADER_TEXT_ADMINISTRATION_PROFILES', 'Beheer Profielen');
define('HEADER_TEXT_ADMINISTRATION_EMPLOYEES', 'Beheer Medewerkers');
define('HEADER_TEXT_ADMINISTRATION_EMPLOYEES_ROLES', 'Beheer Medewerker-Rollen');
define('HEADER_TEXT_ADMINISTRATION_UNITS', 'Beheer Eenheden');
define('HEADER_TEXT_ADMINISTRATION_TARIFFS', 'Beheer Tarieven');
define('HEADER_TEXT_ADMINISTRATION_TIMESHEETS', 'Beheer Timesheets');
define('HEADER_TEXT_ADMINISTRATION_BENEFITS', 'Beheer Verlofkaarten');

define('BOX_HEADING_MAINMENU', 'Menu');
define('BOX_MAINMENU_HOME', 'Home');
define('BOX_MAINMENU_TIMEREGISTRATION', 'Tijdregistratie');
define('BOX_MAINMENU_TIMEREGISTRATION_PUNCH_CLOCK', 'Prikklok');
define('BOX_MAINMENU_TIMEREGISTRATION_CALENDAR', 'Kalender');
define('BOX_MAINMENU_ANALYSIS', 'Rapportage');
define('BOX_MAINMENU_ADMINISTRATION', 'Beheer');
define('BOX_MAINMENU_ADMINISTRATION_GENERAL', 'Algemeen');
define('BOX_MAINMENU_ADMINISTRATION_PROFILES', 'Profielen');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES', 'Medewerkers');
define('BOX_MAINMENU_ADMINISTRATION_TIMESHEETS', 'Timesheets');
define('BOX_MAINMENU_ADMINISTRATION_BENEFITS', 'Verlofkaarten');
define('BOX_MAINMENU_ADMINISTRATION_SYSTEM', 'Systeem');
define('BOX_MAINMENU_ADMINISTRATION_CUSTOMERS', 'Debiteuren');
define('BOX_MAINMENU_ADMINISTRATION_BUSINESS_UNITS', 'Business Units');
define('BOX_MAINMENU_ADMINISTRATION_CATEGORIES', 'Categorie&euml;n');
define('BOX_MAINMENU_ADMINISTRATION_UNITS', 'Eenheden');
define('BOX_MAINMENU_ADMINISTRATION_PROJECTS', 'Projecten');
define('BOX_MAINMENU_ADMINISTRATION_ROLES', 'Rollen');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES_ROLES', 'Medewerker-Rollen');
define('BOX_MAINMENU_ADMINISTRATION_TARIFFS', 'Tarieven');

define('BOX_HEADING_BENEFITS', 'Verlofsaldo');
define('BOX_BENEFITS_CREDIT', 'Vorig tegoed :');
define('BOX_BENEFITS_GRANTED', 'Dit jaar :');
define('BOX_BENEFITS_TOTAL', 'Totaal :');
define('BOX_BENEFITS_USED', 'Gebruikt :');
define('BOX_BENEFITS_REMAINING', 'Te besteden :');
define('BOX_BENEFITS_HOURS', ' uur');
define('BOX_BENEFITS_DAYS', ' dgn');

define('BODY_TEXT_LOGIN', 'Login');
define('BODY_TEXT_PASSWORD', 'Wachtwoord');
define('BODY_TEXT_PASSWORD_VERIFY', 'Herhaal wachtwoord');
define('BODY_TEXT_FULLNAME', 'Volledige naam');
define('BODY_TEXT_EMPLOYEE_ID', 'Personeelsnummer');
define('BODY_TEXT_PROFILE', 'Profiel');
define('BODY_TEXT_YES', 'Ja');
define('BODY_TEXT_NO', 'Nee');
define('BODY_TEXT_NOT_APPLICABLE', 'n.v.t.');
$LOGIN_ERROR_LEVEL = array (0 => '',
                            1 => 'Inloggen mislukt',
                            2 => 'Wachtwoorden komen niet overeen',
                            3 => 'Het wachtwoord mag niet leeg zijn');

define('TEXT_ENTRY_NEW', 'Nieuw');
define('TEXT_ENTRY_SAVE', 'Opslaan');
define('TEXT_ENTRY_CANCEL', 'Annuleren');
define('TEXT_ENTRY_EDIT', 'Wijzigen');
define('TEXT_ENTRY_DELETE', 'Verwijderen');
define('TEXT_ENTRY_DELETE_QUESTION', 'Wilt u deze ingave verwijderen?');
define('TEXT_ENTRY_DELETE_OK', 'Verwijderen OK');
define('TEXT_ENTRY_DELETE_CANCEL', 'Verwijderen annuleren');

define('TEXT_TIMEREGISTRATION_BACK', 'Periode terug');
define('TEXT_TIMEREGISTRATION_PERIOD', 'Periode : ');
define('TEXT_TIMEREGISTRATION_FORWARD', 'Periode vooruit');
define('TEXT_TIMEREGISTRATION_LOCKED', 'Deze periode is bevestigd en kan niet meer worden gewijzigd');
define('TEXT_TIMEREGISTRATION_IS_EMPTY', 'Geen activiteiten aanwezig');
define('TEXT_TIMEREGISTRATION_CONFIRM', 'Bevestig deze periode. LET OP: Hierna zijn wijzigingen niet meer mogelijk!');
define('TEXT_TIMEREGISTRATION_CONFIRM_QUESTION', 'Wilt u deze periode bevestigen?');
define('TEXT_TIMEREGISTRATION_CONFIRM_QUESTION_MINIMUM_HOURS_NOT_MET', 'Minimum aantal uren niet gehaald, wilt u deze periode toch bevestigen?');
define('TEXT_TIMEREGISTRATION_CONFIRM_OK', 'Bevestigen OK');
define('TEXT_TIMEREGISTRATION_CONFIRM_CANCEL', 'Bevestigen annuleren');
define('TEXT_TIMEREGISTRATION_CALENDAR_DESCRIPTION', 'Overzicht geboekte aantallen per dag:');
define('TEXT_TIMEREGISTRATION_TICKET_LOOKUP_IS_EMPTY', 'Geen te selecteren tickets gevonden.');

define('TEXT_PROJECTS', 'Projecten');
define('TEXT_PROJECTS_NAME', 'Project naam');
define('TEXT_PROJECTS_DESCRIPTION', 'Omschrijving');
define('TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME', 'Contactpersoon klant');
define('TEXT_PROJECTS_CUSTOMERS_REFERENCE', 'Referentie klant');
define('TEXT_PROJECTS_START_DATE', 'Start datum');
define('TEXT_PROJECTS_END_DATE', 'Eind datum');
define('TEXT_PROJECTS_CALCULATED_HOURS', 'Gebudgetteerde uren');
define('TEXT_PROJECTS_CALCULATED_HOURS_PERIOD', 'Meetperiode');
$PROJECTS_CALCULATED_HOURS_PERIOD = array ('B' => 'Per billing periode',
                                           'E' => 'Volledig project');
define('TEXT_PROJECTS_HOURS_USED', 'Gebruikte uren');
define('TEXT_PROJECTS_HOURS_USED_PERCENTAGE', 'Gebruikte uren (%)');
define('TEXT_PROJECTS_QUESTION_ER1', 'Start datum medewerker-rollen wijzigen');
define('TEXT_PROJECTS_QUESTION_T1', 'Start datum tarieven wijzigen');
define('TEXT_PROJECTS_QUESTION_ER2', 'Eind datum medewerker-rollen wijzigen');
define('TEXT_PROJECTS_QUESTION_T2', 'Eind datum tarieven wijzigen');
define('TEXT_PROJECTS_SHOW_HISTORY', 'Toon historie');
define('TEXT_PROJECTS_LISTING_IS_EMPTY', 'Geen projecten aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$PROJECT_ERROR_LEVEL = array (0  => '',
                              1  => 'Project naam ontbreekt',
                              2  => 'Start datum ontbreekt',
                              3  => 'Business Unit ontbreekt',
                              4  => 'Debiteur ontbreekt',
                              5  => 'Datum onjuist',
                              6  => 'Gebudgetteerde uren onjuist',
                              7  => 'Medewerker-Rollen aanwezig voor nieuwe start datum',
                              8  => 'Tarieven aanwezig voor nieuwe start datum',
                              9  => 'Activiteiten tussen oude en nieuwe start datum',
                              10 => 'Medewerker-Rollen aanwezig na nieuwe eind datum',
                              11 => 'Tarieven aanwezig na nieuwe eind datum',
                              12 => 'Activiteiten aanwezig tussen oude en nieuwe eind datum',
                              13 => 'Verwijderen niet toegestaan, rollen aanwezig');

define('TEXT_ROLES', 'Rol');
define('TEXT_ROLES_NAME', 'Naam');
define('TEXT_ROLES_DESCRIPTION', 'Omschrijving');
define('TEXT_ROLES_MANDATORY_TICKET_ENTRY', 'Ticketnr verplicht');
define('TEXT_ROLES_CATEGORY', 'Categorie');
define('TEXT_ROLES_LISTING_IS_EMPTY', 'Geen rollen aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$ROLE_ERROR_LEVEL = array (0 => '',
                           1 => 'Rol naam ontbreekt',
                           2 => 'Categorie ontbreekt',
                           3 => 'Verwijderen niet toegestaan, medewerker-rollen aanwezig');

define('TEXT_EMPLOYEES_ROLES', 'Medewerker-Rol');
define('TEXT_EMPLOYEES_ROLES_START_DATE', 'Start datum');
define('TEXT_EMPLOYEES_ROLES_END_DATE', 'Eind datum');
define('TEXT_EMPLOYEES_ROLES_QUESTION_T1', 'Start datum tarieven wijzigen');
define('TEXT_EMPLOYEES_ROLES_QUESTION_T2', 'Eind datum tarieven wijzigen');
define('TEXT_EMPLOYEES_ROLES_LISTING_IS_EMPTY', 'Geen medewerker-rollen geselecteerd of aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$EMPLOYEE_ROLE_ERROR_LEVEL = array (0  => '',
                                    1  => 'Rol ontbreekt',
                                    2  => 'Medewerker ontbreekt',
                                    3  => 'Start datum ontbreekt',
                                    4  => 'Datum onjuist',
                                    5  => 'Start datum voor project start datum',
                                    6  => 'Eind datum na project eind datum',
                                    7  => 'Duplicaten aangetroffen',
                                    8  => 'Tarieven aanwezig voor nieuwe start datum',
                                    9  => 'Activiteiten tussen oude en nieuwe start datum',
                                    10 => 'Tarieven aanwezig na nieuwe eind datum',
                                    11 => 'Activiteiten aanwezig tussen oude en nieuwe eind datum',
                                    12 => 'Verwijderen niet toegestaan, tarieven aanwezig');

define('TEXT_TARIFFS', 'Tarief');
define('TEXT_TARIFFS_AMOUNT', 'Bedrag');
define('TEXT_TARIFFS_START_DATE', 'Start datum');
define('TEXT_TARIFFS_END_DATE', 'Eind datum');
define('TEXT_TARIFFS_LISTING_IS_EMPTY', 'Geen tarieven aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$TARIFF_ERROR_LEVEL = array (0  => '',
                             1  => 'Bedrag ontbreekt',
                             2  => 'Eenheid ontbreekt',
                             3  => 'Start datum ontbreekt',
                             4  => 'Bedrag onjuist',
                             5  => 'Datum onjuist',
                             6  => 'Start datum voor medewerker-rol start datum',
                             7  => 'Eind datum na medewerker-rol eind datum',
                             8  => 'Duplicaten aangetroffen',
                             9  => 'Activiteiten tussen oude en nieuwe start datum',
                             10 => 'Activiteiten aanwezig tussen oude en nieuwe eind datum',
                             11 => 'Verwijderen niet toegestaan, activiteiten aanwezig');

define('TEXT_TIMESHEETS_PERIOD', 'Periode');
define('TEXT_TIMESHEETS_START_DATE', 'Start datum');
define('TEXT_TIMESHEETS_END_DATE', 'Eind datum');
define('TEXT_TIMESHEETS_LOCK', 'Vergrendel');
define('TEXT_TIMESHEETS_UNLOCK', 'Ontgrendel');
define('TEXT_TIMESHEETS_UNCONFIRMED', 'Onbevestigd');
define('TEXT_TIMESHEETS_LISTING_IS_EMPTY', 'Geen timesheets aanwezig');

define('TEXT_ACTIVITY_DAY', 'Dag');
define('TEXT_ACTIVITY_PROJECTNAME', 'Project');
define('TEXT_ACTIVITY_ROLENAME', 'Rol');
define('TEXT_ACTIVITY_AMOUNT', 'Aantal');
define('TEXT_ACTIVITY_UNIT', 'Eenheid');
define('TEXT_ACTIVITY_TRAVELDISTANCE', 'Reisafstand (km)');
define('TEXT_ACTIVITY_EXPENSES', 'Onkosten (&euro;)');
define('TEXT_ACTIVITY_TICKETNUMBER', 'Ticket nr.');
define('TEXT_ACTIVITY_TICKETNUMBER_LOOKUP', 'Ticket omschrijving ophalen');
define('TEXT_ACTIVITY_COMMENT', 'Opmerkingen');
define('TEXT_ACTIVITY_COPY', 'Laatste activiteit van vorige (werk-)dag kopi&euml;ren');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$ACTIVITY_ERROR_LEVEL = array (0  => '',
                               1  => 'Geen project gekozen',
                               2  => 'Geen rol gekozen',
                               3  => 'Aantal onjuist',
                               4  => 'Geen eenheid gekozen',
                               5  => 'Reisafstand onjuist',
                               6  => 'Onkosten onjuist',
                               7  => 'Ticket nr. is verplicht',
                               32 => 'Overschrijding gebudgetteerde uren',
                               33 => 'Activiteit niet geldig op geselecteerde datum',
                               64 => 'Geen bruikbare gegevens beschikbaar');

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

define('TEXT_PUNCH_CLOCK_START', 'Start');
define('TEXT_PUNCH_CLOCK_STOP', 'Stop');
define('TEXT_PUNCH_CLOCK_TIME', 'Tijd');
define('TEXT_PUNCH_CLOCK_ACTIVITY', 'Activiteit');
define('TEXT_PUNCH_CLOCK_IS_EMPTY', 'Geen entries aanwezig');
define('TEXT_PUNCH_CLOCK_TOTAL', 'TOTAAL');

define('TEXT_ANALYSIS_BACK', 'Periode terug');
define('TEXT_ANALYSIS_PERIOD', 'Periode : ');
define('TEXT_ANALYSIS_FORWARD', 'Periode vooruit');

define('TEXT_BUTTON_PDF', 'Maak .pdf bestand');
define('TEXT_BUTTON_CSV', 'Exporteer naar .csv bestand');
define('REPORT_NAME_EMPLOYEES', 'Overzicht medewerkers');
define('REPORT_NAME_PROJECTS', 'Overzicht projecten');
define('REPORT_NAME_TRAVEL_DISTANCES_AND_EXPENSES', 'Overzicht reisafstanden en onkosten');
define('REPORT_NAME_CONSOLIDATED_PROJECTS_PER_EMPLOYEE', 'Geconsolideerd overzicht per medewerker');
define('REPORT_NAME_TIMESHEETS', 'Overzicht urenstaten');
define('REPORT_EMPLOYEES_ID', 'nr');
define('REPORT_EMPLOYEES_FULLNAME', 'Naam');
define('REPORT_EMPLOYEES_LOGIN', 'Inloggen');
define('REPORT_EMPLOYEES_PROJECTLISTING', 'Projectlst');
define('REPORT_EMPLOYEES_TIMEREGISTRATION', 'Urenadm');
define('REPORT_EMPLOYEES_ANALYSIS', 'Analyse');
define('REPORT_EMPLOYEES_ADMINISTRATION', 'Admin');
define('REPORT_EMPLOYEES_TIMESHEET_AVAILABLE', 'Timesheet');
define('REPORT_EMPLOYEES_TIMESHEET_LOCKED', 'Bevestigd');
define('REPORT_EMPLOYEES_TRAVEL_DISTANCE', 'Reisafstand (km)');
define('REPORT_EMPLOYEES_EXPENSES', 'Onkosten');

define('REPORT_TEXT_CUSTOMER_NAME', 'Klant:');
define('REPORT_TEXT_DATE', 'Datum:');
define('REPORT_TEXT_PERIOD', 'Periode:');
define('REPORT_TEXT_PROJECT_NAME', 'Project:');
define('REPORT_TEXT_ROLE_NAME', 'Rol:');
define('REPORT_TEXT_EMPLOYEE_NAME', 'Medewerker:');
define('REPORT_TEXT_FOOTER_SIGNATURE_EMPLOYEE', 'Handtekening medewerker');
define('REPORT_TEXT_FOOTER_SIGNATURE_CUSTOMER', 'Handtekening klant');
define('REPORT_TEXT_FOOTER_ACKNOWLEDGE', 'Door ondertekening van deze urenstaat verklaart de opdrachtgever zich akkoord met de gespecificeerde aantallen, eenheden, kilometers en onkosten.');

define('REPORT_CHECKBOX_SHOW_USER_RIGHTS', 'Toon gebruikersrechten');
define('REPORT_CHECKBOX_SHOW_TIMESHEET_INFO', 'Toon timesheet info');
define('REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE_AND_EXPENSES', 'Toon reisafstand en onkosten');
define('REPORT_CHECKBOX_SHOW_ALL_EMPLOYEES', 'Toon alle (ex-)medewerkers');
define('REPORT_CHECKBOX_PER_EMPLOYEE', 'Per medewerker');
define('REPORT_CHECKBOX_SHOW_TARIFF', 'Toon tarieven');
define('REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE', 'Toon reisafstanden');
define('REPORT_CHECKBOX_SHOW_EXPENSES', 'Toon onkosten');
define('REPORT_CHECKBOX_SHOW_COMMENTS', 'Toon opmerkingen');
define('REPORT_CHECKBOX_SHOW_SIGNATURE', 'Toon handtekeningvelden');

define('REPORT_TABLE_HEADER_DATE', 'Datum');
define('REPORT_TABLE_HEADER_EMPLOYEE_NAME', 'Medewerker');
define('REPORT_TABLE_HEADER_PROJECT_NAME', 'Project');
define('REPORT_TABLE_HEADER_ROLE_NAME', 'Rol');
define('REPORT_TABLE_HEADER_ACTIVITY_AMOUNT', 'Aantal');
define('REPORT_TABLE_HEADER_UNIT_NAME', 'Eenheid');
define('REPORT_TABLE_HEADER_IS_TARIFF', ' a ');
define('REPORT_TABLE_HEADER_TARIFF', 'Tarief');
define('REPORT_TABLE_HEADER_TRAVEL_DISTANCE', 'km');
define('REPORT_TABLE_HEADER_TRAVEL_DESCRIPTION', 'omschrijving');
define('REPORT_TABLE_HEADER_EXPENSES', 'Onkosten');
define('REPORT_TABLE_HEADER_TICKET_NUMBER', 'Ticket nr');
define('REPORT_TABLE_HEADER_TOTAL', 'Totaal');
define('REPORT_TABLE_HEADER_COMMENT', 'Opmerkingen');

define('EXPORT_NAME_ACTIVITIES', 'Export activiteiten');

define('TEXT_CUSTOMERS', 'Debiteur');
define('TEXT_CUSTOMERS_ID', 'Debiteurnr');
define('TEXT_CUSTOMERS_NAME', 'Naam');
define('TEXT_CUSTOMERS_BILLING_NAME1', 'Factuurnaam 1');
define('TEXT_CUSTOMERS_BILLING_NAME2', 'Factuurnaam 2');
define('TEXT_CUSTOMERS_BILLING_ADDRESS', 'Factuuradres');
define('TEXT_CUSTOMERS_BILLING_POSTCODE', 'Facturatie postcode');
define('TEXT_CUSTOMERS_BILLING_CITY', 'Facturatie plaats');
define('TEXT_CUSTOMERS_BILLING_COUNTRY', 'Facturatie land');
define('TEXT_CUSTOMERS_BILLING_EMAIL_ADDRESS', 'Facturatie e-mail');
define('TEXT_CUSTOMERS_BILLING_PHONE', 'Facturatie telefoonnr');
define('TEXT_CUSTOMERS_BILLING_FAX', 'Facturatie faxnr');
define('TEXT_CUSTOMERS_LISTING_IS_EMPTY', 'Geen debiteuren aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$CUSTOMER_ERROR_LEVEL = array (0  => '',
                               1  => 'Debiteurnummer ontbreekt',
                               2  => 'Debiteur naam ontbreekt',
                               3  => 'Debiteurnummer onjuist',
                               4  => 'Duplicaat debiteurnummer',
                               5  => 'Verwijderen niet toegestaan, projecten aanwezig');

define('TEXT_PROFILE', 'Profiel');
define('TEXT_PROFILES_NAME', 'Naam');
define('TEXT_PROFILES_RIGHTS_LOGIN', 'Inloggen');
define('TEXT_PROFILES_RIGHTS_PROJECTLISTING', 'Projectlst');
define('TEXT_PROFILES_RIGHTS_TIMEREGISTRATION', 'Urenadm');
define('TEXT_PROFILES_RIGHTS_ANALYSIS', 'Analyse');
define('TEXT_PROFILES_RIGHTS_ADMINISTRATION', 'Admin');
define('TEXT_PROFILES_LISTING_IS_EMPTY', 'Geen profielen aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$PROFILE_ERROR_LEVEL = array (0 => '',
                              1 => 'Profielnaam ontbreekt',
                              2 => 'Verwijderen niet toegestaan, medewerkers aanwezig');

define('TEXT_EMPLOYEES', 'Medewerker');
define('TEXT_EMPLOYEES_ID', 'Medewerkernr');
define('TEXT_EMPLOYEES_LOGIN', 'Login');
define('TEXT_EMPLOYEES_FULLNAME', 'Volledige naam');
define('TEXT_EMPLOYEES_RESET_PASSWORD', 'Reset wachtwoord');
define('TEXT_EMPLOYEES_LISTING_IS_EMPTY', 'Geen medewerkers aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$EMPLOYEE_ERROR_LEVEL = array (0 => '',
                               1 => 'Medewerkernummer ontbreekt',
                               2 => 'Login ontbreekt',
                               3 => 'Volledige naam ontbreekt',
                               4 => 'Profiel ontbreekt',
                               5 => 'Medewerkernummer onjuist',
                               6 => 'Duplicaat medewerkernummer',
                               7 => 'Verwijderen niet toegestaan, medew.-rollen/timesheets aanwezig');

define('TEXT_BUSINESS_UNITS', 'Business Unit');
define('TEXT_BUSINESS_UNITS_NAME', 'Naam');
define('TEXT_BUSINESS_UNITS_IMAGE', 'Logo');
define('TEXT_BUSINESS_UNITS_IMAGE_POSITION', 'Positie');
define('TEXT_BUSINESS_UNITS_LISTING_IS_EMPTY', 'Geen business units aanwezig');
$BUSINESS_UNITS_IMAGE_POSITION = array ('L' => 'Links',
                                        'C' => 'Midden',
                                        'R' => 'Rechts');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$BUSINESS_UNIT_ERROR_LEVEL = array (0 => '',
                                    1 => 'Naam ontbreekt',
                                    2 => 'Bestandstype onjuist',
                                    3 => 'Doellocatie niet schrijfbaar',
                                    4 => 'Doellocatie niet aanwezig',
                                    5 => 'Geen bestand ge-upload',
                                    6 => 'Bestand niet opgeslagen',
                                    7 => 'Verwijderen niet toegestaan, projecten aanwezig');

define('TEXT_CATEGORIES', 'Categorie');
define('TEXT_CATEGORIES_NAME', 'Naam');
define('TEXT_CATEGORIES_LISTING_IS_EMPTY', 'Geen categorie&euml;n aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$CATEGORY_ERROR_LEVEL = array (0 => '',
                               1 => 'Naam ontbreekt',
                               2 => 'Verwijderen niet toegestaan, rollen aanwezig');

define('TEXT_UNITS', 'Eenheid');
define('TEXT_UNITS_NAME', 'Naam');
define('TEXT_UNITS_DESCRIPTION', 'Omschrijving');
define('TEXT_UNITS_LISTING_IS_EMPTY', 'Geen eenheden aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$UNIT_ERROR_LEVEL = array (0 => '',
                           1 => 'Naam ontbreekt',
                           2 => 'Verwijderen niet toegestaan, tarieven aanwezig');

define('TEXT_BENEFITS', 'TEXT_BENEFITS');
define('TEXT_BENEFITS_ROLE', 'Type');
define('TEXT_BENEFITS_PROPOSAL', 'Voorstel');
define('TEXT_BENEFITS_CREDIT', 'Vorig tegoed');
define('TEXT_BENEFITS_GRANTED', 'Toegekend');
define('TEXT_BENEFITS_TOTAL', 'Totaal');
define('TEXT_BENEFITS_USED', 'Gebruikt');
define('TEXT_BENEFITS_REMAINING', 'Restant');
define('TEXT_BENEFITS_START_DATE', 'Start datum');
define('TEXT_BENEFITS_END_DATE', 'Eind datum');
define('TEXT_BENEFITS_COMMENT', 'Opmerkingen');
define('TEXT_BENEFITS_LISTING_IS_EMPTY', 'Geen medewerker geselecteerd of geen verlofkaart(en) aanwezig');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$BENEFIT_ERROR_LEVEL = array (0  => '',
                               1  => 'Rol ontbreekt',
                               2  => 'Medewerker ontbreekt',
                               3  => 'Start datum ontbreekt',
                               4  => 'Datum onjuist',
                               5  => 'Start datum voor project start datum',
                               6  => 'Eind datum na project eind datum',
                               7  => 'Duplicaten aangetroffen',
                               8  => 'Tarieven aanwezig voor nieuwe start datum',
                               9  => 'Activiteiten tussen oude en nieuwe start datum',
                               10 => 'Tarieven aanwezig na nieuwe eind datum',
                               11 => 'Activiteiten aanwezig tussen oude en nieuwe eind datum',
                               12 => 'Verwijderen niet toegestaan, tarieven aanwezig');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . COMPANY_NAME . '</a>');
?>