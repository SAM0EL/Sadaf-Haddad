document.addEventListener("scroll", function () {
    let widget = document.querySelector(".textwidget.custom-html-widget");
    let footer = document.querySelector("footer");

    let footerTop = footer.getBoundingClientRect().top;
    let windowHeight = window.innerHeight;

    if (footerTop <= windowHeight) {
        widget.style.position = "absolute";
        widget.style.bottom = "0";
    } else {
        widget.style.position = "fixed";
        widget.style.bottom = "0px";
    }
});


jQuery(document).ready(function($){ 
    if(!($('body').hasClass("home"))) {
        console.log("الصفحة ليست الصفحة الرئيسية، جارٍ تعديل الروابط...");
        
        // استهداف العنصر الأول
        $("#split_right-menu .anker-menu a").each(function(){
            var href = $(this).attr('href');
            console.log("الرابط الأصلي (split_right-menu):", href);
            
            if (href && !href.startsWith("https://tacverse.com/healthylife/")) {
                var newHref = "https://tacverse.com/healthylife/" + href.replace(/^\//, '');
                console.log("الرابط الجديد (split_right-menu):", newHref);
                $(this).attr("href", newHref);
            }
        });
        
        // استهداف العنصر الثاني
        $("#split_left-menu .anker-menu a").each(function(){
            var href = $(this).attr('href');
            console.log("الرابط الأصلي (split_left-menu):", href);
            
            if (href && !href.startsWith("https://almalakchurch.com/")) {
                var newHref = "https://almalakchurch.com/" + href.replace(/^\//, '');
                console.log("الرابط الجديد (split_left-menu):", newHref);
                $(this).attr("href", newHref);
            }
        });

        $("#mobile-menu .anker-menu a").each(function(){
            var href = $(this).attr('href');
            console.log("الرابط الأصلي (split_left-menu):", href);
            
            if (href && !href.startsWith("https://almalakchurch.com/")) {
                var newHref = "https://almalakchurch.com/" + href.replace(/^\//, '');
                console.log("الرابط الجديد (split_left-menu):", newHref);
                $(this).attr("href", newHref);
            }
        });
    }
});