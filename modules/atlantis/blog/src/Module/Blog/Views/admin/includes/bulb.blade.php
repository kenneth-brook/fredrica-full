<div class="helper">
    <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
    <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
        <ul class="accordion" data-accordion>
            <li class="accordion-item is-active" data-accordion-item>
                <a href="#" class="accordion-title">Showing single/individual blog items</a>
                <div class="accordion-content" data-tab-content>
                    To show individual blog items, you can use the element <b> &lt;div data-pattern-func="module:blog@single"&gt;&lt;/div&gt;</b> in any page.

                </div>
            </li>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">Showing All Blog Items</a>
                <div class="accordion-content" data-tab-content>
                    To show all blog items, you can use the element <b>&lt;div data-pattern-func="module:blog@all"&gt;&lt;/div&gt;</b> in any page. 
                </div>
            </li>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">Showing Recent</a>
                <div class="accordion-content" data-tab-content>
                    To show 3 recent post (short) <b>&lt;div data-pattern-func="module:blog@recentshort-3"&gt;&lt;/div&gt;</b> in any page. 
                </div>
            </li>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">Showing previous / next buttons</a>
                <div class="accordion-content" data-tab-content>
                    Usuly right after blog single pattern func  <b>&lt;div data-pattern-func="module:blog@quicklinks"&gt;&lt;/div&gt;</b> in any page. 
                </div>
            </li>
             <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">More pattern functions</a>
                <div class="accordion-content" data-tab-content>
                    data-pattern-func="module:blog@archivePosts show post by month url example /blog/2018/3<br>
                    data-pattern-func="module:blog@archiveDatesList" - show list of available dates<br>
                    data-pattern-func="module:blog@showPostsByCategory" <br>
                    data-pattern-func="module:blog@categoriesList" - show list of available Categories<br>data-pattern-func="module:blog@showTagCloud" - tag cloud widget<br>
                    data-pattern-func="module:blog@relatedPostsWidget" - to show 2 related posts<br>
                    data-pattern-func="module:blog@shareWidget" - to show social share buttons (fb, twitter & linkedin)<br>

                </div>
            </li>
        </ul>
    </div>
</div>