<?php use_helper('AccessibleForm');?>
<div id="recent_dishes">
<h2>Recent Dishes</h2>
<?php include_component('menuitem', 'feature') ?>
</div>
<div id="review_form">
  <h2>&#8230;so what have you been eating?</h2>
  
  <?php echo accessible_form_tag('#') ?>
  <fieldset>
    <legend>Delivery Details</legend>
    <ol>
      <li>
        <label for="name">Name<em>*</em></label>
        <input id="name" />
      </li>
      <li>
        <label for="address1">Address<em>*</em></label>
        <input id="address1" />
      </li>
      <li>
        <label for="address2">Address 2</label>
        <input id="address2" />
      </li>
      <li>
        <label for="town-city">Town/City</label>
        <input id="town-city" />
      </li>
      <li>
        <label for="county">County<em>*</em></label>
        <input id="county" />
      </li>
      <li>
        <label for="postcode">Postcode<em>*</em></label>
        <input id="postcode" />
      </li>
      <li>
        <fieldset>
          <legend>Is this address also your invoice »
  address?<em>*</em></legend>
          <label><input type="radio" »
  name="invoice-address" /> Yes</label>
          <label><input type="radio" »
  name="invoice-address" /> No</label>
        </fieldset>
      </li>
    </ol>
  </fieldset>
    </form>
</div>