(function() {
  var $ipEl;
  var $timeContainer;
  var $timeEl;

  $(function(){
    getEls();
    bind();
    prettyPrint();
    getIP();
  });

  function getIP() {
    var startTime = new Date();

    $ipEl.hide();
    $timeEl.hide();

    ipService.get(function(user){
      var endTime = new Date();

      $ipEl.html(user.ip);
      $timeEl.html((endTime-startTime)/1000);

      $ipEl.fadeIn('slow');
      $timeEl.fadeIn('slow');
    });

    return false; //stop prop and prev def if we are in a click handler
  }

  function getEls() {
    $ipEl = $('.user-ip').find('span');
    $timeContainer = $('.user-time');
    $timeEl = $timeContainer.find('span');
  }

  function bind() {
    $timeContainer.on('click','a',getIP);
  }
}());