<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->



<?php use_helper('AccessibleForm');?>
<div id="recent_dishes">
<h2>Recent Dishes</h2>
<?php //include_component('menuitem', 'feature') ?>
</div>
<div id="review_form">
  <h2>&#8230;so what have you been eating?</h2>
  
  <?php echo accessible_form_tag('#') ?>
    <fieldset>
      <ol>
        <li>
          <div id="restaurantAutoComplete">
            <label for="restaurantInput">I ate at...</label>
            <?php echo input_tag('restaurantInput',null,'class=text') ?>
            <div id="restaurantACContainer"></div>
            <?php echo input_hidden_tag('restaurant_id') ?>
          </div>
        </li>
        <li>
          <label for="address1">and had...</em></label>
          <input id="address1" />
        </li>
        <li>
          <label for="address2">it cost</label>
          <input id="address2" />
        </li>
        <li>
          <label for="town-city">How was it?</label>
          <input id="town-city" />
        </li>
        <li>
          <label for="county">I'd rate it?</label>
          <input id="county" />
        </li>
      </ol>
    </fieldset>
  </form>
</div>