/* ================================================
            Flip Counter Script
  ================================================ */
       // var api;
        
       //  //Countdown timer
       //  api = $(".countdown").flipTimer({
       //    date:"2023/12/10 00:00:00",
       //    bgColor:"#a60000",
       //    timeZone:-5,  //Time zone of New York
       //    past:true,
       //    borderRadius:2,
       //  });


        if ($("#countdown").length>0) {
          $("#countdown").flipTimer({
            date:"2023/12/25 20:30:00",
            dayTextNumber:"auto",
            bgColor:"#fff",
            dividerColor:"#666",
            digitColor:"#333",
            textColor:"#fff",
            boxShadow:false,

            //Expire
            expireType:"message", //message, hide, redirect
            message:"Sorry, you are too late!",
            redirect:""
        });
      }