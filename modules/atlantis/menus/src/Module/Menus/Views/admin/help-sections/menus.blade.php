<div class="helper">
  <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
  <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
    <ul class="accordion" data-accordion>
      <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Call from editor</a>
        <div class="accordion-content" data-tab-content>
          <p>with cache - true/false</p>
          <p>{{ '<div data-pattern-func="module:menu@buildByID-11,true">&nbsp;</div>' }}</p>
        </div>
      </li>
      <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Call from code</a>
        <div class="accordion-content" data-tab-content>
          <p>with cache - true/false</p>
          <p><?= "{!! \Module\Menus\Helpers\MenuBuilder::buildByID([11,false]) !!}" ?></p>
        </div>
      </li>
      <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Placeholders</a>
        <div class="accordion-content" data-tab-content>
          You can use placeholders into tthe items url field.
          <br>
          <kbd>@{{domain}}</kbd> - Use this to add the site domain. Usefull for using with subdomains. Example <kbd>mysubdomain.@{{domain}}/path</kbd><br>You need to specify the domain in the <a href="/admin/config">site admin</a>, otherwise it may cause issues in some cases</div>
      </li>
    </ul>
  </div>
</div>