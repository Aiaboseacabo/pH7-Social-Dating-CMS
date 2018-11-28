<?php
/**
 * @title          Main Controller
 *
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Admin / Controller
 */

namespace PH7;

use PH7\Framework\Layout\Html\Meta;
use PH7\Framework\Mvc\Model\DbConfig;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Security\Version;
use PH7\Framework\Url\Header;

class MainController extends Controller
{
    public function index()
    {
        // Add ph7cms-helper's JS file if needed
        $oValidateSite = new ValidateSiteCore($this->session);
        if ($oValidateSite->needToInject()) {
            $oValidateSite->injectJsSuggestionBox($this->design);
        }

        $this->view->page_title = t('Admin Panel');
        $this->view->h1_title = t('Admin Dashboard');
        $this->view->h2_title = t('Hi <em>%0%</em>! Welcome back to your site!', $this->session->get('admin_first_name'));
        $this->view->h3_title = t('How are you doing today?');

        $this->view->is_news_feed = (bool)DbConfig::getSetting('isSoftwareNewsFeed');

        $this->checkUpdates();

        $this->addStats();

        $this->output();
    }

    public function stat()
    {
        $this->view->page_title = t('Statistics');
        $this->view->h1_title = t('Site statistics');

        $this->addStats();

        $this->output();
    }

    public function login()
    {
        // Prohibit the referencing in search engines of the admin panel
        $this->view->header = Meta::NOINDEX;

        $this->view->page_title = t('Sign in to Admin Panel');
        $this->view->h1_title = t('Admin Panel - Login');
        $this->output();
    }

    public function logout()
    {
        (new Admin)->logout($this->session);

        Header::redirect(
            Uri::get(PH7_ADMIN_MOD, 'main', 'login'),
            t('You are successfully logged out.')
        );
    }

    protected function addStats()
    {
        $this->addCssFile();

        $oStatModel = new StatisticCoreModel;

        // Get the since date of the website
        $this->view->since_date = $this->dateTime->get(StatisticCoreModel::getDateOfCreation())->date();


        //---------- Number of Logins Members ----------//

        // All Members
        $this->view->today_login_members = $oStatModel->totalLogins(DbTableName::MEMBER, 1);
        $this->view->week_login_members = $oStatModel->totalLogins(DbTableName::MEMBER, 7);
        $this->view->month_login_members = $oStatModel->totalLogins(DbTableName::MEMBER, 31);
        $this->view->year_login_members = $oStatModel->totalLogins(DbTableName::MEMBER, 365);
        $this->view->login_members = $oStatModel->totalLogins();

        // Men Members
        $this->view->today_login_male_members = $oStatModel->totalLogins(DbTableName::MEMBER, 1, GenderTypeUserCoreModel::MALE);
        $this->view->week_login_male_members = $oStatModel->totalLogins(DbTableName::MEMBER, 7, GenderTypeUserCoreModel::MALE);
        $this->view->month_login_male_members = $oStatModel->totalLogins(DbTableName::MEMBER, 31, GenderTypeUserCoreModel::MALE);
        $this->view->year_login_male_members = $oStatModel->totalLogins(DbTableName::MEMBER, 365, GenderTypeUserCoreModel::MALE);
        $this->view->login_male_members = $oStatModel->totalLogins(DbTableName::MEMBER, 0, GenderTypeUserCoreModel::MALE);

        // Women Members
        $this->view->today_login_female_members = $oStatModel->totalLogins(DbTableName::MEMBER, 1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_login_female_members = $oStatModel->totalLogins(DbTableName::MEMBER, 7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_login_female_members = $oStatModel->totalLogins(DbTableName::MEMBER, 31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_login_female_members = $oStatModel->totalLogins(DbTableName::MEMBER, 365, GenderTypeUserCoreModel::FEMALE);
        $this->view->login_female_members = $oStatModel->totalLogins(DbTableName::MEMBER, 0, GenderTypeUserCoreModel::FEMALE);

        // Couple Members
        $this->view->today_login_couple_members = $oStatModel->totalLogins(DbTableName::MEMBER, 1, GenderTypeUserCoreModel::COUPLE);
        $this->view->week_login_couple_members = $oStatModel->totalLogins(DbTableName::MEMBER, 7, GenderTypeUserCoreModel::COUPLE);
        $this->view->month_login_couple_members = $oStatModel->totalLogins(DbTableName::MEMBER, 31, GenderTypeUserCoreModel::COUPLE);
        $this->view->year_login_couple_members = $oStatModel->totalLogins(DbTableName::MEMBER, 365, GenderTypeUserCoreModel::COUPLE);
        $this->view->login_couple_members = $oStatModel->totalLogins(DbTableName::MEMBER, 0, GenderTypeUserCoreModel::COUPLE);


        //---------- Number of Logins Affiliates ----------//

        // All Affiliates
        $this->view->today_login_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 1);
        $this->view->week_login_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 7);
        $this->view->month_login_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 31);
        $this->view->year_login_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 365);
        $this->view->login_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE);

        // Men Affiliates
        $this->view->today_login_male_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 1, GenderTypeUserCoreModel::MALE);
        $this->view->week_login_male_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 7, GenderTypeUserCoreModel::MALE);
        $this->view->month_login_male_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 31, GenderTypeUserCoreModel::MALE);
        $this->view->year_login_male_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 365, GenderTypeUserCoreModel::MALE);
        $this->view->login_male_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 0, GenderTypeUserCoreModel::MALE);

        // Women Affiliates
        $this->view->today_login_female_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_login_female_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_login_female_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_login_female_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 365, GenderTypeUserCoreModel::FEMALE);
        $this->view->login_female_affiliate = $oStatModel->totalLogins(DbTableName::AFFILIATE, 0, GenderTypeUserCoreModel::FEMALE);


        //---------- Number of Logins Admins ----------//

        // All Admins
        $this->view->today_login_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 1);
        $this->view->week_login_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 7);
        $this->view->month_login_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 31);
        $this->view->year_login_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 365);
        $this->view->login_admins = $oStatModel->totalLogins(DbTableName::ADMIN);

        // Men Admins
        $this->view->today_login_male_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 1, GenderTypeUserCoreModel::MALE);
        $this->view->week_login_male_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 7, GenderTypeUserCoreModel::MALE);
        $this->view->month_login_male_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 31, GenderTypeUserCoreModel::MALE);
        $this->view->year_login_male_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 365, GenderTypeUserCoreModel::MALE);
        $this->view->login_male_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 0, GenderTypeUserCoreModel::MALE);

        // Women Admins
        $this->view->today_login_female_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_login_female_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_login_female_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_login_female_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 365, GenderTypeUserCoreModel::FEMALE);
        $this->view->login_female_admins = $oStatModel->totalLogins(DbTableName::ADMIN, 0, GenderTypeUserCoreModel::FEMALE);


        //---------- Members Registrations ----------//

        // All Members
        $this->view->today_total_members = $oStatModel->totalMembers(1);
        $this->view->week_total_members = $oStatModel->totalMembers(7);
        $this->view->month_total_members = $oStatModel->totalMembers(31);
        $this->view->year_total_members = $oStatModel->totalMembers(365);
        $this->view->total_members = $oStatModel->totalMembers();

        // Men Members
        $this->view->today_total_male_members = $oStatModel->totalMembers(1, GenderTypeUserCoreModel::MALE);
        $this->view->week_total_male_members = $oStatModel->totalMembers(7, GenderTypeUserCoreModel::MALE);
        $this->view->month_total_male_members = $oStatModel->totalMembers(31, GenderTypeUserCoreModel::MALE);
        $this->view->year_total_male_members = $oStatModel->totalMembers(365, GenderTypeUserCoreModel::MALE);
        $this->view->total_male_members = $oStatModel->totalMembers(0, GenderTypeUserCoreModel::MALE);

        // Women Members
        $this->view->today_total_female_members = $oStatModel->totalMembers(1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_total_female_members = $oStatModel->totalMembers(7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_total_female_members = $oStatModel->totalMembers(31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_total_female_members = $oStatModel->totalMembers(365, GenderTypeUserCoreModel::FEMALE);
        $this->view->total_female_members = $oStatModel->totalMembers(0, GenderTypeUserCoreModel::FEMALE);

        // Couple Members
        $this->view->today_total_couple_members = $oStatModel->totalMembers(1, GenderTypeUserCoreModel::COUPLE);
        $this->view->week_total_couple_members = $oStatModel->totalMembers(7, GenderTypeUserCoreModel::COUPLE);
        $this->view->month_total_couple_members = $oStatModel->totalMembers(31, GenderTypeUserCoreModel::COUPLE);
        $this->view->year_total_couple_members = $oStatModel->totalMembers(365, GenderTypeUserCoreModel::COUPLE);
        $this->view->total_couple_members = $oStatModel->totalMembers(0, GenderTypeUserCoreModel::COUPLE);


        //---------- Affiliates Registrations ----------//

        // All Affiliates
        $this->view->today_total_affiliate = $oStatModel->totalAffiliates(1);
        $this->view->week_total_affiliate = $oStatModel->totalAffiliates(7);
        $this->view->month_total_affiliate = $oStatModel->totalAffiliates(31);
        $this->view->year_total_affiliate = $oStatModel->totalAffiliates(365);
        $this->view->total_affiliate = $oStatModel->totalAffiliates();

        // Men Affiliates
        $this->view->today_total_male_affiliate = $oStatModel->totalAffiliates(1, GenderTypeUserCoreModel::MALE);
        $this->view->week_total_male_affiliate = $oStatModel->totalAffiliates(7, GenderTypeUserCoreModel::MALE);
        $this->view->month_total_male_affiliate = $oStatModel->totalAffiliates(31, GenderTypeUserCoreModel::MALE);
        $this->view->year_total_male_affiliate = $oStatModel->totalAffiliates(365, GenderTypeUserCoreModel::MALE);
        $this->view->total_male_affiliate = $oStatModel->totalAffiliates(0, GenderTypeUserCoreModel::MALE);

        // Women Affiliates
        $this->view->today_total_female_affiliate = $oStatModel->totalAffiliates(1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_total_female_affiliate = $oStatModel->totalAffiliates(7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_total_female_affiliate = $oStatModel->totalAffiliates(31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_total_female_affiliate = $oStatModel->totalAffiliates(365, GenderTypeUserCoreModel::FEMALE);
        $this->view->total_female_affiliate = $oStatModel->totalAffiliates(0, GenderTypeUserCoreModel::FEMALE);


        //---------- Admins Registrations ----------//

        // All Admins
        $this->view->today_total_admins = $oStatModel->totalAdmins(1);
        $this->view->week_total_admins = $oStatModel->totalAdmins(7);
        $this->view->month_total_admins = $oStatModel->totalAdmins(31);
        $this->view->year_total_admins = $oStatModel->totalAdmins(365);
        $this->view->total_admins = $oStatModel->totalAdmins();

        // Men Admins
        $this->view->today_total_male_admins = $oStatModel->totalAdmins(1, GenderTypeUserCoreModel::MALE);
        $this->view->week_total_male_admins = $oStatModel->totalAdmins(7, GenderTypeUserCoreModel::MALE);
        $this->view->month_total_male_admins = $oStatModel->totalAdmins(31, GenderTypeUserCoreModel::MALE);
        $this->view->year_total_male_admins = $oStatModel->totalAdmins(365, GenderTypeUserCoreModel::MALE);
        $this->view->total_male_admins = $oStatModel->totalAdmins(0, GenderTypeUserCoreModel::MALE);

        // Women Admins
        $this->view->today_total_female_admins = $oStatModel->totalAdmins(1, GenderTypeUserCoreModel::FEMALE);
        $this->view->week_total_female_admins = $oStatModel->totalAdmins(7, GenderTypeUserCoreModel::FEMALE);
        $this->view->month_total_female_admins = $oStatModel->totalAdmins(31, GenderTypeUserCoreModel::FEMALE);
        $this->view->year_total_female_admins = $oStatModel->totalAdmins(365, GenderTypeUserCoreModel::FEMALE);
        $this->view->total_female_admins = $oStatModel->totalAdmins(0, GenderTypeUserCoreModel::FEMALE);


        //---------- Blogs ----------//

        $this->view->today_total_blogs = $oStatModel->totalBlogs(1);
        $this->view->week_total_blogs = $oStatModel->totalBlogs(7);
        $this->view->month_total_blogs = $oStatModel->totalBlogs(31);
        $this->view->year_total_blogs = $oStatModel->totalBlogs(365);
        $this->view->total_blogs = $oStatModel->totalBlogs();


        //---------- Notes ----------//

        $this->view->today_total_notes = $oStatModel->totalNotes(1);
        $this->view->week_total_notes = $oStatModel->totalNotes(7);
        $this->view->month_total_notes = $oStatModel->totalNotes(31);
        $this->view->year_total_notes = $oStatModel->totalNotes(365);
        $this->view->total_notes = $oStatModel->totalNotes();


        //---------- Messages ----------//

        $this->view->today_total_mails = $oStatModel->totalMails(1);
        $this->view->week_total_mails = $oStatModel->totalMails(7);
        $this->view->month_total_mails = $oStatModel->totalMails(31);
        $this->view->year_total_mails = $oStatModel->totalMails(365);
        $this->view->total_mails = $oStatModel->totalMails();


        //---------- Comments ----------//

        // Profile Comments
        $this->view->today_total_profile_comments = $oStatModel->totalProfileComments(1);
        $this->view->week_total_profile_comments = $oStatModel->totalProfileComments(7);
        $this->view->month_total_profile_comments = $oStatModel->totalProfileComments(31);
        $this->view->year_total_profile_comments = $oStatModel->totalProfileComments(365);
        $this->view->total_profile_comments = $oStatModel->totalProfileComments();

        // Picture Comments
        $this->view->today_total_picture_comments = $oStatModel->totalPictureComments(1);
        $this->view->week_total_picture_comments = $oStatModel->totalPictureComments(7);
        $this->view->month_total_picture_comments = $oStatModel->totalPictureComments(31);
        $this->view->year_total_picture_comments = $oStatModel->totalPictureComments(365);
        $this->view->total_picture_comments = $oStatModel->totalPictureComments();

        // Video Comments
        $this->view->today_total_video_comments = $oStatModel->totalVideoComments(1);
        $this->view->week_total_video_comments = $oStatModel->totalVideoComments(7);
        $this->view->month_total_video_comments = $oStatModel->totalVideoComments(31);
        $this->view->year_total_video_comments = $oStatModel->totalVideoComments(365);
        $this->view->total_video_comments = $oStatModel->totalVideoComments();

        // Blog Comments
        $this->view->today_total_blog_comments = $oStatModel->totalBlogComments(1);
        $this->view->week_total_blog_comments = $oStatModel->totalBlogComments(7);
        $this->view->month_total_blog_comments = $oStatModel->totalBlogComments(31);
        $this->view->year_total_blog_comments = $oStatModel->totalBlogComments(365);
        $this->view->total_blog_comments = $oStatModel->totalBlogComments();

        // Note Comments
        $this->view->today_total_note_comments = $oStatModel->totalNoteComments(1);
        $this->view->week_total_note_comments = $oStatModel->totalNoteComments(7);
        $this->view->month_total_note_comments = $oStatModel->totalNoteComments(31);
        $this->view->year_total_note_comments = $oStatModel->totalNoteComments(365);
        $this->view->total_note_comments = $oStatModel->totalNoteComments();

        // Game Comments
        $this->view->today_total_game_comments = $oStatModel->totalGameComments(1);
        $this->view->week_total_game_comments = $oStatModel->totalGameComments(7);
        $this->view->month_total_game_comments = $oStatModel->totalGameComments(31);
        $this->view->year_total_game_comments = $oStatModel->totalGameComments(365);
        $this->view->total_game_comments = $oStatModel->totalGameComments();


        unset($oStatModel);
    }

    /**
     * @return void
     */
    protected function checkUpdates()
    {
        if (Version::isUpdateEligible()) {
            $aLatestVerInfo = Version::getLatestInfo();
            $sLatestVer = t('%0%, build %1%', $aLatestVerInfo['version'], $aLatestVerInfo['build']);

            $sMsg = t('%software_name% <strong>%0%</strong> is available! Please <a href="%software_website%" target="_blank" rel="noopener">update it today</a> to keep your site safe and stable.', $sLatestVer);
            $sMsg .= '<br /><br />';
            $sMsg .= t('Read <a href="%0%" target="_blank" rel="noopener">here</a> to learn how to upgrade your site, step-by-step. Once you follow the steps, run the upgrade wizard <a href="%1%" target="_blank" rel="noopener">here</a>.', 'https://ph7cms.com/doc/en/upgrade', PH7_URL_ROOT . 'asset/file/Upgrade');

            $this->design->setMessage($sMsg);
        }
    }

    /**
     * Adding the common CSS for the statistic chart.
     *
     * @return void
     */
    private function addCssFile()
    {
        $this->design->addCss(
            PH7_LAYOUT . PH7_SYS . PH7_MOD . $this->registry->module . PH7_SH . PH7_TPL . PH7_TPL_MOD_NAME . PH7_SH . PH7_CSS,
            'general.css'
        );
    }
}
