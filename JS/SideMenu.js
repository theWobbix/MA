function openSideMenu(){
    document.getElementById("side-menu").style.boxShadow = "1px 5px 7px 1px rgba(24, 24, 24, 0.767)";
    document.getElementById("side-menu").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px";
}

function closeSideMenu(){
    document.getElementById("side-menu").style.width = "0px";

    setTimeout(() => {
    document.getElementById("side-menu").style.boxShadow = "1px 5px 7px 1px rgba(24, 24, 24, 0)";        
    }, 350);    
    //document.getElementById("main").style.marginLeft = "0px";
}

/*
Der herauskommentierte Code schiebt den rest der Website beim öffnen des
Sidenavs nach rechts. Dadurch sieht es dann so aus als ob das Sidenav den
Rest der Website nach rechts schieben würde.
*/
