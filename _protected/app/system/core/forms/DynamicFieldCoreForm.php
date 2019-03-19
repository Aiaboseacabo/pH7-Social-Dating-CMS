<?php
/**
 * @title          Generate a dynamic form from database fields
 *
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2013-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Core / Form
 */

namespace PH7;

use PH7\Framework\Session\Session;

defined('PH7') or exit('Restricted access');

class DynamicFieldCoreForm
{
    /** @var \PFBC\Form */
    private $oForm;

    /** @var string */
    private $sColumn;

    /** @var string */
    private $sVal;

    /**
     * @param \PFBC\Form $oForm
     * @param string $sColumn Column name
     * @param string $sValue Field value
     */
    public function __construct(\PFBC\Form $oForm, $sColumn, $sValue)
    {
        $this->oForm = $oForm;
        $this->sColumn = $sColumn;
        $this->sVal = $sValue;
    }

    /**
     * Generate the dynamic form.
     *
     * @param string $sSex Profile gender.
     *
     * @return \PFBC\Form
     */
    public function generate($sSex)
    {
        switch ($this->sColumn) {
            case 'description':
                $this->oForm->addElement(new \PFBC\Element\Textarea(t('Description:'), $this->sColumn, ['id' => $this->getFieldId('str'), 'onblur' => 'CValid(this.value,this.id,20,4000)', 'value' => $this->sVal, 'validation' => new \PFBC\Validation\Str(20, 4000), 'required' => 1]));
                $this->addCheckErrSpan('str');
                break;

            case 'country':
                $this->oForm->addElement(new \PFBC\Element\Country(t('Country:'), $this->sColumn, ['id' => $this->getFieldId('str'), 'value' => $this->sVal, 'required' => 1]));
                break;

            case 'city':
                $this->oForm->addElement(new \PFBC\Element\Textbox(t('City:'), $this->sColumn, ['id' => $this->getFieldId('str'), 'onblur' => 'CValid(this.value,this.id,2,150)', 'value' => $this->sVal, 'validation' => new \PFBC\Validation\Str(2, 150), 'required' => 1]));
                $this->addCheckErrSpan('str');
                break;

            case 'state':
                $this->oForm->addElement(new \PFBC\Element\Textbox(t('State:'), $this->sColumn, ['id' => $this->getFieldId('str'), 'onblur' => 'CValid(this.value,this.id,2,150)', 'value' => $this->sVal, 'validation' => new \PFBC\Validation\Str(2, 150)]));
                $this->addCheckErrSpan('str');
                break;

            case 'zipCode':
                $this->oForm->addElement(new \PFBC\Element\Textbox(t('Postal Code:'), $this->sColumn, ['id' => $this->getFieldId('str'), 'onblur' => 'CValid(this.value,this.id,2,15)', 'value' => $this->sVal, 'validation' => new \PFBC\Validation\Str(2, 15)]));
                $this->addCheckErrSpan('str');
                break;

            case 'middleName':
                $this->oForm->addElement(new \PFBC\Element\Textbox(t('Middle Name:'), $this->sColumn, ['id' => $this->getFieldId('name'), 'onblur' => 'CValid(this.value,this.id)', 'value' => $this->sVal, 'validation' => new \PFBC\Validation\Name]));
                $this->addCheckErrSpan('name');
                break;

            case 'height':
                $this->oForm->addElement(new \PFBC\Element\Height(t('Height:'), $this->sColumn, ['value' => $this->sVal]));
                break;

            case 'weight':
                $this->oForm->addElement(new \PFBC\Element\Weight(t('Weight:'), $this->sColumn, ['value' => $this->sVal]));
                break;

            case 'propertyPrice':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Range(
                            t('Price Range'),
                            SearchQueryCore::PRICE,
                            [
                                'value' => $this->sVal,
                                'min' => Form::MIN_PRICE,
                                'max' => Form::MAX_PRICE,
                                'step' => Form::RANGE_NUMBER_INTERVAL
                            ]
                        )
                    );
                }
                break;

            case 'propertyBedrooms':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Bedrooms:'),
                            SearchQueryCore::BEDROOM,
                            [0, 1, 2, 3, 4, 5, 6],
                            ['value' => $this->sVal]
                        )
                    );
                }
                break;

            case 'propertyBathrooms':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Bathrooms:'),
                            SearchQueryCore::BATHROOM,
                            [0, 1, 2, 3, 4],
                            ['value' => $this->sVal]
                        )
                    );
                }
                break;

            case 'propertyYearBuilt':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Number(
                            t('Year Built:'),
                            SearchQueryCore::YEAR_BUILT,
                            [
                                'value' => (isset($this->sVal) ? $this->sVal : date('Y') - 20),
                                'min' => 0,
                                'max' => date('Y')
                            ]
                        )
                    );
                }
                break;

            case 'propertyHomeType':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Home Type:'),
                            SearchQueryCore::HOME_TYPE,
                            [
                                'family' => t('Single Family'),
                                'condo' => t('Condo/Townhouse')
                            ],
                            ['value' => $this->sVal]
                        )
                    );
                }
                break;

            case 'propertyHomeStyle':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Home Style:'),
                            SearchQueryCore::HOME_STYLE,
                            [
                                'rambler' => t('Rambler'),
                                'ranch' => t('Ranch/Patio'),
                                'tri-multi-level' => t('Tri-Multi-Level'),
                                'two-story' => t('Two Story'),
                                'other' => t('Other')
                            ],
                            [
                                'value' => $this->sVal
                            ]
                        )
                    );
                }
                break;

            case 'propertySquareFeet':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Number(
                            t('Square Feet:'),
                            SearchQueryCore::HOME_SQUARE_FT,
                            ['value' => (!empty($this->sVal) ? $this->sVal : 0), 'min' => 0]
                        )
                    );
                }
                break;

            case 'propertyLotSize':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Number(
                            t('Lot Size (acres):'),
                            SearchQueryCore::HOME_LOT_SIZE,
                            ['value' => (!empty($this->sVal) ? $this->sVal : 0), 'min' => 0]
                        )
                    );
                }
                break;

            case 'propertyGarageSpaces':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Garage Spaces:'),
                            SearchQueryCore::HOME_GARAGE_SPACE,
                            [0, 1, 2, 3, 4],
                            ['value' => $this->sVal]
                        )
                    );
                }
                break;

            case 'propertyCarportSpaces':
                if ($sSex === 'seller' || $sSex === 'both') {
                    $this->oForm->addElement(
                        new \PFBC\Element\Select(
                            t('Carport Spaces:'),
                            SearchQueryCore::HOME_CARPORT_SPACE,
                            [0, 1, 2],
                            ['value' => $this->sVal]
                        )
                    );
                }
                break;

            case 'phone':
                $this->oForm->addElement(new \PFBC\Element\Phone(t('Phone Number:'), $this->sColumn, ['id' => $this->getFieldId('phone'), 'onblur' => 'CValid(this.value, this.id)', 'title' => t('Enter full number with area code.'), 'value' => $this->sVal]));
                $this->addCheckErrSpan('phone');
                break;

            case 'contactTimes':
                $this->oForm->addElement(
                    new \PFBC\Element\Select(
                        t('Best time to be contacted:'),
                        $this->sColumn,
                        [
                            'morning' => t('Morning (8.30am - 1pm)'),
                            'afternoon' => t('Afternoon (1pm - 5.30pm)'),
                            'evening' => t('Evening (5.30pm - 9pm)')
                        ],
                        ['value' => $this->sVal]
                    )
                );
                break;

            case 'website':
            case 'socialNetworkSite':
                $sLabel = $this->sColumn === 'socialNetworkSite' ? t('Social Media Profile:') : t('Website:');
                $sDesc = $this->sColumn === 'socialNetworkSite' ? t('The URL of your social profile, such as Facebook, Twitter or LinkedIn.') : t('Your Personal Website/Blog (any promotional/affiliated contents will be removed)');
                $this->oForm->addElement(new \PFBC\Element\Url($sLabel, $this->sColumn, ['id' => $this->getFieldId('url'), 'onblur' => 'CValid(this.value,this.id)', 'description' => $sDesc, 'value' => $this->sVal]));
                $this->addCheckErrSpan('url');
                break;

            default:
                $sLangKey = strtolower($this->sColumn);
                $sClass = '\PFBC\Element\\' . $this->getFieldType();
                $this->oForm->addElement(new $sClass(t($sLangKey), $this->sColumn, ['value' => $this->sVal]));
        }

        return $this->oForm;
    }

    /**
     * @param string $sType
     *
     * @return string
     */
    protected function getFieldId($sType)
    {
        return $sType . '_' . $this->sColumn;
    }

    /**
     * @param string $sType
     *
     * @return void
     */
    protected function addCheckErrSpan($sType)
    {
        $this->oForm->addElement(new \PFBC\Element\HTMLExternal('<span class="input_error ' . $this->getFieldId($sType) . '"></span>'));
    }

    /**
     * Generate other PFBC fields according to the Field Type.
     *
     * @return string PFBC form type.
     */
    protected function getFieldType()
    {
        if (strstr($this->sColumn, 'textarea'))
            $sType = 'Textarea';
        elseif (strstr($this->sColumn, 'editor'))
            $sType = 'CKEditor';
        elseif (strstr($this->sColumn, 'email'))
            $sType = 'Email';
        elseif (strstr($this->sColumn, 'password'))
            $sType = 'Password';
        elseif (strstr($this->sColumn, 'url'))
            $sType = 'Url';
        elseif (strstr($this->sColumn, 'phone'))
            $sType = 'Phone';
        elseif (strstr($this->sColumn, 'date'))
            $sType = 'Date';
        elseif (strstr($this->sColumn, 'color'))
            $sType = 'Color';
        elseif (strstr($this->sColumn, 'number'))
            $sType = 'Number';
        elseif (strstr($this->sColumn, 'range'))
            $sType = 'Range';
        elseif (strstr($this->sColumn, 'height'))
            $sType = 'Height';
        elseif (strstr($this->sColumn, 'weight'))
            $sType = 'Weight';
        else
            $sType = 'Textbox';

        return $sType;
    }
}
