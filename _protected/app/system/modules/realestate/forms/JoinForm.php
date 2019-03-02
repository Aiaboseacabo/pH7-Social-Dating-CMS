<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7cms.com>
 * @copyright      (c) 2012-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / User / Form
 */

namespace PH7;

use PFBC\Validation\CEmail;
use PH7\Framework\Geo\Ip\Geo;
use PH7\Framework\Module\Various as SysMod;
use PH7\Framework\Mvc\Model\DbConfig;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Session\Session;
use PH7\Framework\Url\Header;

class JoinForm
{
    public static function step1()
    {
        if ((new Session)->exists('mail_step1')) {
            Header::redirect(Uri::get('realestate', 'signup', 'step2'));
        }

        if (isset($_POST['submit_join_user'])) {
            if (\PFBC\Form::isValid($_POST['submit_join_user'])) {
                (new JoinFormProcess)->step1();
            }

            Header::redirect();
        }

        $oForm = new \PFBC\Form('form_join_user');
        $oForm->configure(['action' => '']);
        $oForm->addElement(new \PFBC\Element\Hidden('submit_join_user', 'form_join_user'));
        $oForm->addElement(new \PFBC\Element\Token('join'));

        $oForm->addElement(
            new \PFBC\Element\Select(
                t('Are you a?'),
                'sex',
                [
                    'buyer' => t('Home Buyer'),
                    'seller' => t('Home Seller'),
                    'both' => t('Both')
                ],
                ['value' => 'buyer', 'required' => 1]
            )
        );

        $oForm->addElement(new \PFBC\Element\Textbox(t('Your Name'), 'first_name', ['placeholder' => t('First Name'), 'id' => 'name_first', 'onblur' => 'CValid(this.value,this.id)', 'required' => 1, 'validation' => new \PFBC\Validation\Name]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error name_first"></span>'));

        $oForm->addElement(new \PFBC\Element\Username(t('Your Nickname'), 'username', ['placeholder' => t('Nickname'), 'description' => PH7_URL_ROOT . UserCore::PROFILE_PAGE_PREFIX . '<strong><span class="your-user-name">' . t('your-user-name') . '</span><span class="username"></span></strong>', 'id' => 'username', 'required' => 1, 'validation' => new \PFBC\Validation\Username]));

        $oForm->addElement(new \PFBC\Element\Email(t('Your Email'), 'mail', ['placeholder' => t('Email'), 'id' => 'email', 'onblur' => 'CValid(this.value, this.id,\'guest\')', 'required' => 1, 'validation' => new CEmail(CEmail::GUEST_MODE)]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error email"></span>'));

        $oForm->addElement(new \PFBC\Element\Password(t('Your Password'), 'password', ['placeholder' => t('Password'), 'id' => 'password', 'onkeyup' => 'checkPassword(this.value)', 'onblur' => 'CValid(this.value, this.id)', 'required' => 1, 'validation' => new \PFBC\Validation\Password]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error password"></span>'));

        $oForm->addElement(new \PFBC\Element\Textbox(t('Your City'), 'city', ['id' => 'str_city', 'value' => Geo::getCity(), 'onblur' => 'CValid(this.value,this.id,2,150)', 'description' => t('Select the city where you live/where you want to meet people.'), 'validation' => new \PFBC\Validation\Str(2, 150), 'required' => 1]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error str_city"></span>'));

        $oForm->addElement(new \PFBC\Element\Textbox(t('Your Postal Code'), 'zip_code', ['id' => 'str_zip_code', 'value' => Geo::getZipCode(), 'onblur' => 'CValid(this.value,this.id,2,15)', 'validation' => new \PFBC\Validation\Str(2, 15)]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error str_zip_code"></span>'));

        if (DbConfig::getSetting('isCaptchaUserSignup')) {
            $oForm->addElement(new \PFBC\Element\CCaptcha(t('Captcha'), 'captcha', ['placeholder' => t('Captcha'), 'id' => 'ccaptcha', 'onkeyup' => 'CValid(this.value, this.id)', 'description' => t('Enter the below code:')]));
            $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error ccaptcha"></span>'));
        }

        $oForm->addElement(new \PFBC\Element\Checkbox(t('Terms of Service'), 'terms', [1 => '<em>' . t('I have read and agree to the %0%.', '<a href="' . Uri::get('page', 'main', 'terms') . '" rel="nofollow" target="_blank">' . t('Terms of Service') . '</a>') . '</em>'], ['id' => 'terms', 'onblur' => 'CValid(this.checked, this.id)', 'required' => 1]));
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error terms-0"></span>'));
        $oForm->addElement(new \PFBC\Element\Button(t('Join!'), 'submit', ['icon' => 'arrowthick-1-e']));

        // JavaScript Files
        $oForm->addElement(new \PFBC\Element\HTMLExternal('<script src="' . PH7_URL_STATIC . PH7_JS . 'signup.js"></script><script src="' . PH7_URL_STATIC . PH7_JS . 'validate.js"></script>'));

        $oForm->render();
    }

    public static function step2()
    {
        $oSession = new Session;
        if (!$oSession->exists('mail_step1')) {
            Header::redirect(Uri::get('realestate', 'signup', 'step1'));
        } elseif ($oSession->exists('mail_step2')) {
            Header::redirect(Uri::get('realestate', 'signup', 'step3'));
        }
        unset($oSession);

        if (isset($_POST['submit_join_user2'])) {
            if (\PFBC\Form::isValid($_POST['submit_join_user2'])) {
                (new JoinFormProcess)->step2();
            }

            Header::redirect();
        }

        $oForm = new \PFBC\Form('form_join_user2');
        $oForm->configure(['action' => '']);
        $oForm->addElement(new \PFBC\Element\Hidden('submit_join_user2', 'form_join_user2'));
        $oForm->addElement(new \PFBC\Element\Token('join2'));
        $oForm->addElement(new \PFBC\Element\Price);
        $oForm->addElement(new \PFBC\Element\Number(t('Bedrooms'), SearchQueryCore::BEDROOM, ['value' => 0, 'min' => 0]));
        $oForm->addElement(new \PFBC\Element\Number(t('Bathrooms'), SearchQueryCore::BATHROOM, ['value' => 0, 'min' => 0]));
        $oForm->addElement(new \PFBC\Element\Number(t('Size'), SearchQueryCore::SIZE, ['value' => 0, 'size' => 0]));
        $oForm->addElement(
            new \PFBC\Element\Number(
                t('Min Year Built'),
                SearchQueryCore::YEAR_BUILT,
                [
                    'value' => date('Y') - 20,
                    'min' => 0,
                    'max' => date('Y')
                ]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Select(
                t('Home Type'),
                SearchQueryCore::HOME_TYPE,
                [
                    'family' => t('Single Family'),
                    'condo' => t('Condo/Townhouse')
                ]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Select(
                t('Home Style'),
                SearchQueryCore::HOME_STYLE,
                [
                    'rambler' => t('Rambler'),
                    'ranch' => t('Ranch/Patio'),
                    'tri-multi-level' => t('Tri-Multi-Level'),
                    'two-story' => t('Two Story'),
                    'any' => t('Any')
                ]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Number(
                t('Square Feet'),
                SearchQueryCore::HOME_SQUARE_FT,
                ['value' => 0, 'min' => 0]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Number(
                t('Lot Size'),
                SearchQueryCore::HOME_LOT_SIZE,
                ['value' => 0, 'min' => 0]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Number(
                t('Garage Spaces'),
                SearchQueryCore::HOME_GARAGE_SPACE,
                ['value' => 0, 'min' => 0]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Number(
                t('Carport Spaces'),
                SearchQueryCore::HOME_CARPORT_SPACE,
                ['value' => 0, 'min' => 0]
            )
        );
        $oForm->addElement(
            new \PFBC\Element\Date(
                t('Only show listings from this date'),
                SearchQueryCore::FROM_DATE
            )
        );
        $oForm->addElement(new \PFBC\Element\Button(t('Next')));
        $oForm->render();
    }

    public function step3()
    {
        $oSession = new Session;
        if (!$oSession->exists('mail_step2')) {
            Header::redirect(Uri::get('realestate', 'signup', 'step2'));
        } elseif ($oSession->exists('mail_step3')) {
            Header::redirect(Uri::get('realestate', 'signup', 'done'));
        }
        unset($oSession);

        if (isset($_POST['submit_join_user3'])) {
            if (\PFBC\Form::isValid($_POST['submit_join_user3'])) {
                (new JoinFormProcess)->step3();
            }

            Header::redirect();
        }

        $aAvatarFieldOption = ['accept' => 'image/*'];
        $bIsAvatarRequired = DbConfig::getSetting('requireRegistrationAvatar');
        if ($bIsAvatarRequired) {
            $aAvatarFieldOption += ['required' => 1];
        }

        $oForm = new \PFBC\Form('form_join_user3');
        $oForm->configure(['action' => '']);
        $oForm->addElement(new \PFBC\Element\Hidden('submit_join_user3', 'form_join_user3'));
        $oForm->addElement(new \PFBC\Element\Token('join3'));
        $oForm->addElement(new \PFBC\Element\File(t('Photo'), 'avatar', $aAvatarFieldOption));
        $oForm->addElement(new \PFBC\Element\Button(t('Add My Photo')));

        if (!$bIsAvatarRequired) {
            $oForm->addElement(
                new \PFBC\Element\Button(
                    t('Skip'),
                    'submit',
                    ['formaction' => Uri::get('realestate', 'signup', 'done')]
                )
            );
        }
        $oForm->render();
    }
}
