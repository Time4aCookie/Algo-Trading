document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("group-event-toggle").addEventListener("click", function(){
        if(document.getElementById("group-event-container").style.display=="none"){
            document.getElementById("group-event-container").style.display="block";
        }
        else{
            document.getElementById("group-event-container").style.display="none";
            document.getElementById("group-event").value="";
        }
    });
    document.getElementById("create-event").addEventListener("click", function(){
        if(document.getElementById("group-event").value!=""){
            groupEvent(document.getElementById("event-id-hidden").innerHTML);
        }
    });
});
function groupEvent(date){
    const username = String(document.getElementById("login-username").value);
    const description = String(document.getElementById("event-name").value);
    const time = String(document.getElementById("event-time").value);
    const email = String(document.getElementById("group-event").value);
    if (!username || !description || !time) {
        return;
    }
    const query={
        method: "POST",
        headers:{
            //lets the server know we are sending info in url format to put it in the POST
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'email=' + encodeURIComponent(email)
        +'&username='+encodeURIComponent(username)+'&description='+encodeURIComponent(description)+'&time='+encodeURIComponent(date)+encodeURIComponent(time)+':00'
    };
    fetch('groupEvent.php', query)
    //checked til here so far
        .then(function(response){
            if (!response.ok) {
                throw new Error('response status not 200');
            }
            return response.json();
        })
       .then(function(output) {
            if(output.success){
    
        }
       })
       .catch(function(error) {
        console.error('Event creation:', error);
        document.getElementById("event-message").innerText = "Unexpected error...";
        $('#event-message').show();
        $('#event-message').fadeOut(2000);
    });
      
}