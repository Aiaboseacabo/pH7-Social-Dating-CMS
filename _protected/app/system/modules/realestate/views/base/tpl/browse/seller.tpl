<div class="box-left col-md-3 col-lg-3 col-xl-2">
    <div role="search" class="design-box">
        <h2>{lang 'Search Sellers'}</h2>
        {{ SearchQuickSellerForm::display(PH7_WIDTH_SEARCH_FORM) }}
    </div>
</div>

<div class="box-right col-md-9 col-lg-9 col-xl-9 col-xl-offset-1">
    {if empty($users)}
        <p class="center bold">{lang 'Whoops! No properties (sellers) found.'}</p>
    {else}
        {each $user in $users}
            <div class="thumb_photo">
                {{ UserDesignCoreModel::userStatus($user->profileId) }}

                {{ $avatarDesign->get($user->username, $user->firstName, $user->sex, AvatarDesignCore::BROWSE_SELLER_AVATAR_SIZE, false) }}
                <p class="cy_ico">
                    <a href="{% (new UserCore)->getProfileSignupLink($user->username, $user->firstName, $user->sex) %}" title="{lang 'Name: %0%', $user->firstName}<br> {lang 'From %0%', $str->upperFirst($user->city)}<br> {lang 'State: %0%', $str->upperFirst($user->state)}">
                        <strong>{% $str->extract($user->username, PH7_MAX_USERNAME_LENGTH_SHOWN, PH7_ELLIPSIS) %}</strong>
                    </a>
                </p>

                {if $is_admin_auth}
                    <p class="small">
                        <a href="{{ $design->url(PH7_ADMIN_MOD,'user','loginuseras',$user->profileId) }}" title="{lang 'Login As a member'}">{lang 'Login As'}</a> |
                        {if $user->ban == '0'}
                            {{ $design->popupLinkConfirm(t('Ban'), PH7_ADMIN_MOD, 'user', 'ban', $user->profileId) }}
                        {else}
                            {{ $design->popupLinkConfirm(t('UnBan'), PH7_ADMIN_MOD, 'user', 'unban', $user->profileId) }}
                        {/if}
                        | <br />{{ $design->popupLinkConfirm(t('Delete'), PH7_ADMIN_MOD, 'user', 'delete', $user->profileId.'_'.$user->username) }} |
                        {{ $design->ip($user->ip) }}
                    </p>
                {/if}
            </div>
        {/each}

        {main_include 'page_nav.inc.tpl'}
    {/if}
</div>
