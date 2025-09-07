var timeoutID;
resetTimeout();

function resetTimeout(){
    if( timeoutID ) clearTimeout( timeoutID );
    timeoutID = setTimeout( ShowTimeoutWarning, 3000 );
}


function ShowTimeoutWarning() {
    $( "#dialog" ).dialog( "open" );
    return false;
}


$("#dialog").dialog({
    autoOpen: false,
    dialogClass: "no-close",
    position: 'center' ,
    title: 'session',
    draggable: false,
    width : 300,
    height : 200,
    resizable : false,
    modal : true,
    buttons: [{
        text: "OK",
        click: function() {
            ShowTimeoutWarning();
            $( this ).dialog( "close" ); 
        }
    }]
});

document.onkeyup   = resetTimeout;
document.onkeydown = resetTimeout;
document.onclick   = resetTimeout;