
$(document).ready(function(){
  function createProto(randId){
    let html = '<div class="col-12 form-group comment new">';
        html +=   '<div class="row justify-content-between">';
        html +=     '<input type="hidden" name="items['+randId+'][id_item]"/>';
        html +=     '<input type="hidden" class="item-action" name="items['+randId+'][action]" value="new"/>';
        html +=     '<input type="text" class="col-10 input-value" name="items['+randId+'][desc_item]" required />';
        html +=     '<button type="button" class="col-1 btn btn-sm btn-danger del-item"><i class="fas fa-trash"></i></button>';
        html +=   '</div>';
        html += '</div>';
    return html;
  }
  function showOrHideNoComments(nb_comment) {
    if (nb_comment === 0){
      $("#no-comment").show();
    }else{
      $("#no-comment").hide();
    }
  }
  function launchListener(){
    showOrHideNoComments($(".comment").length);
    $(".del-item").off().click(function(){
      let parent = $(this).parent().parent();
      if (parent.hasClass('new')){
        parent.remove();
        launchListener();
      }else{
        if (parent.hasClass('edit')){
          parent.addClass('delete');
          parent.removeClass('edit');
          $(".item-action", parent).val("delete");
          $(".input-value", parent).prop('disabled', true);
        }else{
          parent.addClass('edit');
          parent.removeClass('delete');
          $(".item-action", parent).val("edit");
          $(".input-value", parent).prop('disabled', false);
        }
      }
    });

  }
  $("#add-comment").click(function(){
    let randId =  Math.random().toString(16).slice(2);
    let proto = createProto(randId);
    $("#comments").append(proto);
    launchListener();
  });
  launchListener();
});