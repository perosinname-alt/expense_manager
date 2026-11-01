loadData();

btnAction = "Insert";

fillLinks();
fillCategories();
$("#addNew").on("click", function() {
    btnAction = "Insert";
    $("#linkForm")[0].reset();
    $("#update_id").val('');
    $("#linkModal").modal("show");
});

$("#linkForm").on("submit", function(event) {

    event.preventDefault();
    let name = $("#name").val();
    let link_id = $("#link_id").val();
    let category_id = $("#category_id").val();
    let id = $("#update_id").val();
     let sendingData = {}
    if(btnAction == "Insert"){
    sendingData = {
        "name": name,
        "link": link_id,
        "category": category_id,
        "action": "register_link"
    
    };
}else{
    sendingData = {
        "id": id,
        "name": name,
        "link": link_id,
        "category": category_id,
        "action": "update_link"
}
}
    $.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/system_links.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            if(status) {
                

               displayMessage("success",response);
               btnAction = "Insert";
               loadData();
            } else {
               displayMessage("error",response);
            }

        },
        error: function(data) {
            displayMessage("error",data.responseText);
        },
    })

})

function displayMessage(type, message) {
    let success = document.querySelector(".alert-success");
    let error = document.querySelector(".alert-danger");

    if (type == "success") {
        error.classList = "alert alert-danger d-none";   // qari error-ka
        success.classList = "alert alert-success";
        success.innerHTML = message;

   setTimeout(function(){
    $("#linkModal").modal('hide');
    success.classList = "alert alert-success d-none";
    $("#linkForm")[0].reset();
},3000);
    } else {
        success.classList = "alert alert-success d-none";
        error.classList = "alert alert-danger";
        error.innerHTML = message;
    }
}
function loadData() {
    $("#linkTable tbody").html(' ');
    let sendingData = {
        "action": "read_all_db_links"
    }

$.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/system_links.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            let html =' ';
            let tr = ' ';
            let th = ' ';
            if(status) {
                    response.forEach(res => {

                        th += "<tr>";
                        for (let i in res) {
                            th += `<th>${i}</th>`;
                        }
                        th += "<th>Action</th></tr>";
                        tr += "<tr>";
                        tr += `<td>${res['id']}</td>`;
                        tr += `<td>${res['name']}</td>`;
                        tr += `<td>${res['link']}</td>`;
                        tr += `<td>${res['category_id'] ?? ''}</td>`;
                        tr += `<td>${res['date']}</td>`;
                        
            tr += `<td><a class="btn btn-info update_info" update_id=${res['id']}><I
            class="fas fa-edit" style="color: #fff;"></i></a>&nbsp;&nbsp;<a class="btn btn-danger
            delete_info" delete_id=${res['id']}> <i class="fas fa-trash"style="color: #fff;"></i> </a> </td>`;
                 

                              tr += "</tr>";
                    })
                    $("#linkTable thead").append(th);
                    $("#linkTable tbody").append(tr);
              
            } else {
               displayMessage("error",response);
            }

        },
        error: function(data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    })

}
function fillLinks() {
    
    let sendingData = {
        "action": "read_all_system_links"
    }

$.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/system_links.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            let html =' ';
            let tr = ' ';
            if(status) {
                    response.forEach(res => {
                        html += `<option value="${res}">${res}</option>`;

                        
                    })
                    $("#link_id").append(html);
              
            } else {
               displayMessage("error",response);
            }

        },
        error: function(data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    })

}
function fillCategories() {
    
    let sendingData = {
        "action": "read_all_category"
    }

$.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/category.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            let html =' ';
            let tr = ' ';
            if(status) {
                    response.forEach(res => {
                        html += `<option value="${res['id']}">${res['name']}</option>`;

                        
                    })
                    $("#category_id").append(html);
              
            } else {
               displayMessage("error",response);
            }

        },
        error: function(data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    })

}
function fetchlinkInfo(id) {
   let sendingData = {
        "action": "get_link_info",
        "id": id
    }

$.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/system_links.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            let html =' ';
            let tr = ' ';
            if(status) {
               btnAction = "update";
                 $("#update_id").val(response['id']);
                    $("#name").val(response['name']);
                    $("#link_id").val(response['link']);
                    $("#category_id").val(response['category_id']);
                 $("#linkModal").modal("show");
                   
            } else {
               displayMessage("error",response);
            }

        },
        error: function(data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    })

}
function DeletelinkInfo(id) {
   let sendingData = {
        "action": "delete_link_info",
        "id": id
    }

$.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/system_links.php",
        data: sendingData,
        success: function(data) {
            let status = data.status;
            let response = data.data;
            let html =' ';
            let tr = ' ';
            if(status) {
           swal("Good job!", response, "success");
                loadData();   
            } else {
               swal(response);
            }

        },
        error: function(data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    })

}


 
    
$("#linkTable").on("click", "a.update_info"   ,function() {
    let id = $(this).attr("update_id");
fetchlinkInfo(id);
});
    
$("#linkTable").on("click", "a.delete_info"   ,function() {
    let id = $(this).attr("delete_id");
    if(confirm("Are you sure you want to delete this record?")) {
            DeletelinkInfo(id);
    }
    
});