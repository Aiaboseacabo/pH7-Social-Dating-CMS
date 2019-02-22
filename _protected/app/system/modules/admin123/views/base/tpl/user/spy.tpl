<p class="center">
    {if !empty($data)}
        {each $userSpy in $data}
            <div class="row">
                <div class="col-md-2">
                    {lang 'Profile ID:'} <a href="{{ $design->url('cool-profile-page', 'main', 'index', $userSpy->profileId) }}">#{% $userSpy->profileId %}</a>
                </div>
                <div class="col-md-4">
                    {lang 'URL:'} <a href="{% $userSpy->url %}">{% $userSpy->url %}</a>
                </div>
                <div class="col-md-4">
                    {lang 'Action: <em>%0%</em>', $userSpy->userAction}</a>
                </div>
                <div class="col-md-2">
                    {lang 'Date: %0%', $userSpy->lastActivity}
                </div>
            </div>
        {/each}
    {else}
        <p class="err_msg">{lang 'No user interactions found.'}</p>
    {/if}
</div>

{main_include 'page_nav.inc.tpl'}
