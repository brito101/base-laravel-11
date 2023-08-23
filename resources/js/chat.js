const urlChat = $("#chat-component").data("url");
const unreadMessages = $("#unread-messages");
const contactsList = $("#contacts-list");
const messagesBox = $("#messages-box");
let receiver = $("#receiver");
let activeContact = $("#activeContact");
let scrollFlag = false;

let users = [];
let messages = [];

//mark messages read
$("#button-message-open").on("click", function (e) {
    if ($("#message-icon").hasClass("fa-plus")) {
        $.ajax({
            type: "GET",
            url: urlChat + `/read?contact=${receiver.val()}`,
        });
    }
});

// Block auto scroll on move top
let lastScrollTop = 0;
messagesBox.on("scroll", function (e) {
    let st = $(e.currentTarget).scrollTop();
    if (st > lastScrollTop) {
        scrollFlag = false;
    } else {
        scrollFlag = true;
    }
    lastScrollTop = st;
});

function scrollBox() {
    messagesBox.animate(
        {
            scrollTop: 999999999,
        },
        "slow"
    );
}

function updateChat() {
    if (scrollFlag == false) {
        scrollBox();
    }

    //Get messages
    $.ajax({
        type: "GET",
        url: urlChat + `?contact=${receiver.val()}`,
        // url: urlChat,
        success: function (res) {
            // Counter unread messages
            unreadMessages.text(res.unreadMessages);
            activeContact.text(res.name);

            //Lists
            users = res.users;
            messages = res.messages.reverse();

            // Contacts list
            let usersList = "";
            users.forEach((el) => {
                usersList += `<li onClick="changeReceiver(${el.id});">
                                <a href="#"><img class="direct-chat-img" src="${el.photo}" alt ="${el.name}">
                                    <div class="contacts-list-info">
                                        <span class="contacts-list-name">${el.name} <i class="fa fa-circle ${el.active}"></i>
                                            <small class="contacts-list-date float-right">${el.lastMessage.date}</small>
                                        </span>
                                        <span class="contacts-list-msg">${el.lastMessage.message}</span>
                                </div>
                            </li>`;
            });
            contactsList.html(usersList);

            //Active contact Messages
            let messagesList = "";
            receiver.val(res.lastContact);

            messages.forEach((el) => {
                if (messagesBox.data("me") == el.sender_id) {
                    messagesList += `<div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">${
                                            el.sender.name
                                        }</span>
                                        <span class="direct-chat-timestamp float-right">${
                                            el.created_at
                                        }</span>
                                    </div>
                                    <img class="direct-chat-img" src="${
                                        el.senderPhoto
                                    }" alt="${messagesBox.data("name")}">
                                    <div class="direct-chat-text">${
                                        el.message
                                    }</div>
                            </div>`;
                } else {
                    messagesList += `<div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">${el.sender.name}</span>
                                    <span class="direct-chat-timestamp float-left">${el.created_at}</span>
                                </div>
                                <img class="direct-chat-img" src="${el.senderPhoto}"" alt="${el.sender.name}">
                                <div class="direct-chat-text">${el.message}</div>
                            </div>`;
                }
            });
            messagesBox.html(messagesList);
        },
        error: function ({ status, statusText }) {
            console.log(status, statusText);
        },
    });
}

updateChat();

const chatInterval = setInterval(() => {
    updateChat();
}, 3 * 1000);

// Change contact
function changeReceiver(id) {
    receiver.val(id);
    $('*[data-widget="chat-pane-toggle"]').click();
    updateChat();
}

// Remove chat
$('*[data-card-widget="remove"]').on("click", function () {
    clearInterval(chatInterval);
});

// Sending message
$("#chat").on("submit", function (e) {
    e.preventDefault();
    scrollFlag = false;
    scrollBox();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: $("#chat").attr("action"),
        data: $(this).serialize(),
        success: function (res) {
            $("#chat-text").val("");
            updateChat();
        },
    });
});
