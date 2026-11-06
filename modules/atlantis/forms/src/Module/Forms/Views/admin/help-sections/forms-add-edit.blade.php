<div class="helper">
    <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
    <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
        <ul class="accordion" data-accordion>
            <li class="accordion-item is-active" data-accordion-item>
                <a href="#" class="accordion-title">Redirect url</a>
                <div class="accordion-content" data-tab-content>
                    <p>If you have added form on page with url "/index" and
                        <br>REDIRECT URL "<?= '{{url}}/test' ?>". After submit will be redirected to "/index/test"
                        <br>REDIRECT URL "<?= '/test' ?>". After submit will be redirected to "/test"</p>
                </div>
            </li>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">Datepicker</a>
                <div class="accordion-content" data-tab-content>

                    <p>To change the date format, add a custom attribute called <b>format</b> to the form <field></field> with the desired format. See  the <a target="_blank" href="http://www.w3schools.com/php/func_date_date.asp">PHP date format special characters</a> for possible format combinations. </p>
                    <p>If you add a format and add a Datepicker <b>Valid</b> validation your custom format would be matched against users' inputs.</p>
                </div>
            </li>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">Type: Dropdown</a>
                <div class="accordion-content" data-tab-content>
                    <p>You can add the text <em>::checked</em> next to a Dropdown item's value to make the option/dropdown item checked by default.</p>
                    <p>You can add the text <em>::disabled</em> next to a Dropdown item's value to make the option/dropdown item disabled for selection.</p>

                </div>
            </li>
        </ul>
    </div>
</div> 