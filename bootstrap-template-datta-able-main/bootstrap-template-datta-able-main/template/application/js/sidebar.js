loadUserMenus();
setTimeout(function() {
    document.querySelectorAll(".nav-item").forEach( function(item){
        item.addEventListener("click",function(){
         item.classList.toggle("pcoded-trigger");
         item.querySelector(".pcoded-submenu ").classList.toggle("show-menu-now")
        })
    })

}, 2000);
function loadUserMenus(){

    let sendingData = {
        "action" : "getUserMenus"
    }

    $.ajax( {             
        method: "POST",
        dataType: "JSON",
        url : "../api/user_authority.php",
        data : sendingData,
        success: function(data){

            let status = data.status;
            let response = data.data;
            let menuEelement = '';
            let category = '';

            if(status){

                response.forEach( menu => {

                    if(menu['category_name'] !== category){
                        if(category !== ''){
                            menuEelement += '</ul></li>';
                        }
                        menuEelement += `
                        <li data-username="basic components Button Alert Badges breadcrumb Pagination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">${menu['category_name']}</span></a>
                        <ul class="pcoded-submenu">
                        `;
                        category = menu['category_name'];
                    }
                    menuEelement += `

                    <li class=""><a href="${menu['link']}"current_link="${menu['link']}" class="">${menu['link_name']}</a></li>
                        
                            
                    `;
                        


                })

                if(category !== ''){
                    menuEelement += '</ul></li>';
                }

                $("#user_menu").append(menuEelement);

                    let page = window.location.pathname.split("/").pop();
                    let currentPage = document.querySelector(`#user_menu a[current_link='${page}']`);

                    if(currentPage){
                        currentPage.parentElement.classList.add("active");        // li-ga link-ga

                        let parentMenu = currentPage.closest("li.pcoded-hasmenu"); // category-ga
                        if(parentMenu){
                            parentMenu.classList.add("active", "pcoded-trigger");
                            let sub = parentMenu.querySelector(":scope > ul.pcoded-submenu");
                            if(sub) sub.style.display = "block";
                        }
                    }








            }else{
                displayMessage("error",response);
            }
        },
        error : function(data){
            console.log(data);
        }
    })
}

