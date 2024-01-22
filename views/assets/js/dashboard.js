import UserModules from './user.js'

const DashBoardModule = (() => {
    const modal = document.getElementById('modal');
    const overlay = document.getElementById('overlay');
    const modalCloseBtn = document.querySelector('.modal__close-btn');

    const registerEvents = () => {
        overlay.addEventListener('click', closeModal)
        modalCloseBtn.addEventListener('click', closeModal)
    }
    const init = () => {
        UserModules.getUsers().then((response) => {
            processResponseData(response.data);

            document.querySelector('.header__user p').textContent = response.user.name
        });

        registerEvents();
        polling();
    }

    const clearTable = () => {
        const tableBody = document.getElementById('tableBody');
        // Clear existing rows in the table
        while (tableBody.firstChild) {
            tableBody.removeChild(tableBody.firstChild);
        }
    };

    const openModal = () => {
        modal.classList.add("modal-open");
        overlay.classList.add("modal-open");
    }

    const fillModal = (userId) => {
        const modalBody = modal.querySelector('.modal-body');

        UserModules.getUser(userId).then((response) => {
            const user = response.data;

            modalBody.innerHTML = `
            <div><label>Name:</label> <p> ${user.name}</p></div>
            <div><label>Email:</label> <p> ${user.email}</p></div>
            <div><label>User Agent:</label> <p> ${user.user_agent}</p></div>
            <div><label>Entrance Time:</label> <p> ${formatTime(user.entrance_time)}</p></div>
            <div><label>Visit Count:</label> <p> ${user.visit_count}</p></div>
          `;
        })
    }

    const closeModal = () => {
        modal.classList.remove("modal-open");
        overlay.classList.remove("modal-open");
    }

    function formatTimeDifference(updatedAtTimestamp) {
        const currentTime = new Date();
        const updatedTime = new Date(updatedAtTimestamp);

        const timeDifferenceInSeconds = Math.floor((currentTime - updatedTime) / 1000);

        if (timeDifferenceInSeconds < 3) {
            return "just now";
        } else if (timeDifferenceInSeconds < 60) {
            return timeDifferenceInSeconds + " second" + (timeDifferenceInSeconds === 1 ? "" : "s") + " ago";
        } else if (timeDifferenceInSeconds < 3600) {
            const minutes = Math.floor(timeDifferenceInSeconds / 60);
            return minutes + " minute" + (minutes > 1 ? "s" : "") + " ago";
        } else if (timeDifferenceInSeconds < 86400) {
            const hours = Math.floor(timeDifferenceInSeconds / 3600);
            return hours + " hour" + (hours > 1 ? "s" : "") + " ago";
        } else {
            return updatedTime.toLocaleString();
        }
    }

    const addRowToTable = (item) => {
        const tableBody = document.getElementById('tableBody');
        const row = tableBody.insertRow();
        const cell1 = row.insertCell(0);
        const cell2 = row.insertCell(1);
        const cell3 = row.insertCell(2);
        const cell4 = row.insertCell(3);
        const cell5 = row.insertCell(4);
        const cell6 = row.insertCell(5);

        cell1.textContent = item.name ;
        cell2.textContent = formatTime(item.entrance_time);
        cell3.textContent = formatTimeDifference(item.updated_at);
        cell4.textContent = item.user_ip;
        cell5.innerHTML = item.online ? `<span class="online badge">Online</span>` : '<span class="offline badge">Offline</span>'

        const button = document.createElement('button');

        cell6.appendChild(button);
        button.setAttribute('data-id', item.id);

        const imgElement = document.createElement("img");

        imgElement.src = "assets/svg/eye.svg";
        imgElement.alt = "Eye Icon";

        button.appendChild(imgElement)

        button.addEventListener('click', (event) => {

            const itemId = event.currentTarget.getAttribute('data-id');

            openModal()
            fillModal(itemId)
        });
    };

    function formatTime(dateString) {
        let entranceTimeDate = new Date(dateString);

        // Check if the date is valid
        if (isNaN(entranceTimeDate.getTime())) {
            // Invalid date string, return an empty string or handle as needed
            return '';
        }

        // Format the entrance time
        let options = {
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        };

        return entranceTimeDate.toLocaleString('en-US', options);
    }

    const processResponseData = (data) => {
        clearTable();

        const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
        let onlineUsersCount = 0;

        data.forEach(item => {
            const lastOnlineTime = Math.floor(Date.parse(item.updated_at) / 1000);

            const timeDifference = currentTime - lastOnlineTime;

            if (timeDifference < 5) {
                item.online = true;
                onlineUsersCount++;
            }
            addRowToTable(item);
        });

        document.querySelector('.welcome__user-count h1').textContent = onlineUsersCount.toString()
    };

    const polling = () => {
        const pollInterval = setInterval(() => {
            UserModules.polling().then((response) => {
                processResponseData(response.data);
            });
        }, 3000);
    }

    return {
        init
    }
})();

DashBoardModule.init();