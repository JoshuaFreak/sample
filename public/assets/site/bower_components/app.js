(function() {
  'use strict';

  angular.module('app', ['irontec.simpleChat']);

  angular.module('app').controller('Shell', Shell);

  function Shell() {

    var vm = this;

    // vm.messages = [
    //   {
        
    //     'content': 'Hi!',
    //     'username': 'Diorgina',
    //   },
    //   {
    //     'content': 'Whats up?',
    //     'username': 'Diorgina',
    //   },
    //   {
    //     'content': 'I found this nice AngularJS Directive',
    //     'username': 'Diorgina',
    //   },
    //   {
    //     'content': 'Looks Great!',
    //     'username': 'Diorgina',
    //   }
    // ];

    $.ajax({
        url:'chat_message/dataJson', 
        type: "GET", 
        dataType: "json",
        async:false,
        success: function (data) 
        {   
          vm.messages = data;
        }
    });

    vm.username = 'Matt';

    vm.sendMessage = function(message, username) {
      if(message && message !== '' && username) {
        vm.messages.push({
          'content': message,
          'username': username
        });
      }
    };

  }

})();
