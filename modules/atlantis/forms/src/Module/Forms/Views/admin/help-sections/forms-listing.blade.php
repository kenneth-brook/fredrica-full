<div class="helper">
  <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
  <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
    <ul class="accordion" data-accordion>

      <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Pattern Function Calls</a>
        <div class="accordion-content" data-tab-content>
          
          <p><b>{{ '<div data-append-emails="test@mail.com,test1@mail.com" data-pattern-func="module:forms@build-1"></div>' }}</b> - append emails to FORM EMAIL RECIPIENTS </p>
          <p><b>{{ '<div data-emails="test@mail.com,test1@mail.com" data-pattern-func="module:forms@build-1"></div>' }}</b> - overrides FORM EMAIL RECIPIENTS </p>
        </div>
      </li>
    </ul>
  </div>
</div>