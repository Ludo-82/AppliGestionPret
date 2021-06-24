function afficher_cacher() {
    boutton = document.getElementById("cache_button");
    cache1 = document.getElementById("rdvNormal");
    cache2 = document.getElementById("rdvUrgent");
    
    boutton.addEventListener("click",() =>{
    
      if (getComputedStyle(cache1).display == "block"){
        cache1.style.display = "none";
        cache2.style.display = "block";
      }
      else if (getComputedStyle(cache1).display == "none") {
        cache1.style.display = "block";
        cache2.style.display = "none";
      }
    })
    }
    
    function cacher(){
      cache1 = document.getElementById("rdvUrgent");
      cache1.style.display = "none";
    }