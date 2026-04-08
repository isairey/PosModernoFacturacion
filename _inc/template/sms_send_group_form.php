<form id="sms-group-send-form" class="form-horizontal" action="sms/index.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="SENDGROUP">
  <div class="box-body">
    <div class="form-group">
      <label for="people_type" class="col-sm-2 control-label">
        <?php echo trans('label_people_type'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <select id="people_type" class="form-control" name="people_type" >
          <option value=""><?php echo trans('text_select'); ?></option>
          <option value="all_customer">
            <?php echo trans('text_all_customer'); ?>
          </option>
          <option value="all_user">
            <?php echo trans('text_all_user'); ?>
          </option>
          <?php foreach (get_usergroups() as $user_group) : ?>
            <option value="<?php echo $user_group['group_id']; ?>">
              <?php echo $user_group['name']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div ng-show="peopleArray.length" class="form-group">
      <label class="col-sm-2 control-label">
        <?php echo trans('label_people'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6 store-selector">
        <div class="selector">
          <label>
            Total People &raquo; {{ peopleArray.length }}
          </label>
        </div>
        <div class="filter-searchbox">
          <input ng-model="search" class="form-control" type="text" id="search" placeholder="<?php echo trans('search'); ?>">
        </div>
        <div class="well well-sm store-well"> 
          <div>
            <div ng-repeat="people in peopleArray | filter:search">
              <label>                         
                <input type="hidden" name="peoples[{{ people.id }}][mobile_number]" value="{{ people.mobile }}">
                <input type="hidden" name="peoples[{{ people.id }}][name]" value="{{ people.name }}">
                {{ people.name }} ({{ people.mobile }})
              </label>
              <span ng-click="removePeople($index, people.id);" class="fa fa-close text-red pointer"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div ng-show="peopleArray.length" class="form-group">
      <label for="message" class="col-sm-2 control-label">
        <?php echo trans('label_message'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <textarea class="form-control" id="message" name="message" rows="4" required>Hello, [name]</textarea>
        <i>Max character: 160</i>
        <p>
          <kbd>[name]</kbd>
        </p>
      </div>
    </div>
    <div ng-show="peopleArray.length" class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-6">
        <button class="btn btn-block btn-info" id="sms-group-send-btn" type="submit" data-form="#sms-group-send-form" data-loading-text="Sending...">
          <span class="fa fa-fw fa-paper-plane"></span>
          <?php echo trans('button_send'); ?>
        </button>  
      </div>
    </div>
  </div>
</form>