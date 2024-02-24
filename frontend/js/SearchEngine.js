const { createClient } = supabase;

const supabaseUrl = 'https://fnkjgrmynydypuhohefb.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZua2pncm15bnlkeXB1aG9oZWZiIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTcwODIxNDg3NiwiZXhwIjoyMDIzNzkwODc2fQ.Nv7NDE2aNIIuj-PWCHhAi7vmu0OT81t-h_UlE4OcPK4';

const _supabase = createClient(supabaseUrl, supabaseKey);

const initializeFuse = (data) => {
    const fuseOptions = {
        keys: ['fullName'],
        includeScore: true,
        includeMatches: true,
        threshold: 0.3,
    };

    const formattedData = data.map(school => {
        const name = school.naziv;
        const location = school.mjesto;
        const fullName = `${name} - ${location}`;
        return {
            name,
            location,
            fullName,
        };
    });

    return new Fuse(formattedData, fuseOptions);
};

const handleSearchInput = (fuse, autocompleteList, searchInput) => {
    return function () {
        const searchValue = this.value;
        autocompleteList.innerHTML = '';

        const result = fuse.search(searchValue);

        result.slice(0, 5).forEach(item => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';

            // Prikazivanje cijele niske u prijedlogu
            listItem.innerHTML = `<strong>${item.item.fullName}</strong>`;

            listItem.addEventListener('click', function () {
                // Postavljanje vrijednosti na cijelu nisku
                searchInput.value = item.item.fullName;
                autocompleteList.innerHTML = '';
            });

            autocompleteList.appendChild(listItem);
        });
    };
};

const fetchAllSchoolsData = async () => {
    try {
        const { data: schools, error } = await _supabase.from('schools').select('*');

        if (error) {
            throw error;
        }

        const fuse = initializeFuse(schools);

        const searchInput = document.getElementById('searchInput');
        const autocompleteList = document.getElementById('autocompleteList');

        searchInput.addEventListener('input', handleSearchInput(fuse, autocompleteList, searchInput));

        document.getElementById('searchButton').addEventListener('click', async function () {
            const searchValue = searchInput.value;

            const result = fuse.search(searchValue);
            if (result.length > 0) {
                const selectedSchool = result[0].item;

                try {
                    const { data: foundSchools, error } = await _supabase
                        .from('schools')
                        .select('id')
                        .eq('naziv', selectedSchool.name)
                        .eq('mjesto', selectedSchool.location)
                        .single();

                    if (error) {
                        throw error;
                    }

                    showSchedule(foundSchools.id);

                } catch (error) {
                    console.error('Error querying Supabase:', error.message);
                }
            } else {
                displayErrorMessage('Ova škola nije pronađena.');
            }
        });

    } catch (error) {
        console.error('Error fetching schools data:', error.message);
    }
};

fetchAllSchoolsData();

// ...
const scheduleContainer = document.querySelector('.schedule-container');

// ...
function showSchedule(schoolId) {
    console.log('Showing schedule... ' + schoolId);

    const apiUrl = `http://192.168.1.106/api/v1/search-schedule/${schoolId}`;

    axios.get(apiUrl)
        .then(response => {
            if (!response.data || Object.keys(response.data).length === 0) {
                displayErrorMessage('Raspored nije pronađen.');
            } else {
                hideErrorMessage('Raspored nije pronađen.');
                hideSearchEngine();
                showSchoolSchedules(response.data);
            }
        })
        .catch(error => {
            console.error('Greška:', error.message);

            displayErrorMessage('Raspored nije pronađen za ovu školu.');
        });
}
// ...


// ...
function displayErrorMessage(message) {
    const errorMessageDiv = document.getElementById('error-message');

    errorMessageDiv.textContent = message;
    errorMessageDiv.classList.remove('d-none');
}
// ...

// ...
function hideErrorMessage() {
    const errorMessageDiv = document.getElementById('error-message');

    errorMessageDiv.classList.add('d-none');
}
// ...

// ...
function hideSearchEngine() {
    const searchContainer = document.querySelector('.search-container');
    searchContainer.classList.add('d-none');
}
// ...

// ...
function showSchoolSchedules(schoolSchedules) {
    const scheduleLoader = document.querySelector('.schedule__loading-container');
    const rasporedWrapper = document.querySelector('.raspored-wrapper');
    const rasporedBody = document.querySelector('.raspored-wrapper .raspored-body');

    scheduleLoader.style.display = 'none';

    rasporedWrapper.style.display = 'block';

    schoolSchedules.forEach((schoolSchedule, index) => {
        const scheduleData = JSON.parse(schoolSchedule.schedule);

        const schoolInfo = document.createElement('div');
        schoolInfo.innerHTML =
            `
            <h5 class="text-center">${schoolSchedule.name}</h5>
            <p class="text-center text-muted"><strong class="highlight-text">${schoolSchedule.place}</strong></p>
            <p class="text-center text-muted">${schoolSchedule.class_info}</p>
        `

        const table = document.createElement('table');
        table.classList.add('table', 'table-bordered');

        const thead = document.createElement('thead');
        thead.innerHTML = '<tr><th></th><th>Pon</th><th>Uto</th><th>Sri</th><th>Čet</th><th>Pet</th><th>Sub</th><th>Ned</th></tr>';
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        for (let hour = 0; hour <= 8; hour++) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${hour}</td>`;
            const days = ['Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota', 'Nedjelja'];

            days.forEach(day => {
                const subject = scheduleData.subjects.find(d => Object.keys(d)[0] === day);

                if (subject) {
                    const subjectInfo = subject[day].find(s => Object.keys(s)[0] === `Predmet ${hour}`);
                    const subjectName = subjectInfo ? subjectInfo[`Predmet ${hour}`] : '-';
                    tr.innerHTML += `<td>${subjectName}</td>`;
                } else {
                    tr.innerHTML += '<td>-</td>';
                }
            });

            tbody.appendChild(tr);
        }

        table.appendChild(tbody);

        rasporedBody.appendChild(schoolInfo);
        rasporedBody.appendChild(table);

        if (index < schoolSchedules.length - 1) {
            rasporedBody.innerHTML += '<hr>';
        }
    });
}
// ...

// ...
document.addEventListener('DOMContentLoaded', function () {
    var cookieAccepted = localStorage.getItem('cookieAccepted');

    if (!cookieAccepted) {
        var myModal = new bootstrap.Modal(document.getElementById('cookieModal'));
        myModal.show();
    }

    document.getElementById('cookieAcceptButton').addEventListener('click', function () {
        localStorage.setItem('cookieAccepted', true);
        myModal.hide();
    });
});
// ...
