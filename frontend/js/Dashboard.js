import { parseJwt } from "./decodeUser.js";

const { createClient } = supabase;

const supabaseUrl = 'https://fnkjgrmynydypuhohefb.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZua2pncm15bnlkeXB1aG9oZWZiIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTcwODIxNDg3NiwiZXhwIjoyMDIzNzkwODc2fQ.Nv7NDE2aNIIuj-PWCHhAi7vmu0OT81t-h_UlE4OcPK4';

const _supabase = createClient(supabaseUrl, supabaseKey);

let selectedSchool = null;

const initializeFuse = (data) => {
    const formattedSchools = data.map(school => `${school.naziv} - ${school.mjesto}`);

    const fuseOptions = {
        keys: ['name'],
        includeScore: true,
        includeMatches: true,
        threshold: 0.3,
    };

    return new Fuse(formattedSchools.map(skola => ({ name: skola })), fuseOptions);
};

const handleSearchInput = (fuse, autocompleteList, schools) => {
    return function () {
        const searchValue = this.value;
        autocompleteList.innerHTML = '';

        const result = fuse.search(searchValue);

        result.slice(0, 5).forEach(item => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';

            listItem.innerHTML = item.matches.map(match => {
                if (match.key === 'name') {
                    return `<strong>${match.value}</strong>`;
                }
                return match.value;
            }).join('');

            listItem.addEventListener('click', function () {
                const selectedSchoolName = item.item.name;
                selectedSchool = schools.find(school => `${school.naziv} - ${school.mjesto}` === selectedSchoolName);

                if (selectedSchool) {
                    searchInput.value = selectedSchoolName;
                } else {
                    console.error('Nije moguće pronaći ID odabrane škole.');
                }

                autocompleteList.innerHTML = '';
            });

            autocompleteList.appendChild(listItem);
        });
    };
};


function generateJsonData() {
    const subjects = [];
    
    $("#rasporedBody tr").each(function (dayIndex) {
        const dayName = ['Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota', 'Nedjelja'][dayIndex];

        const daySubjects = [];

        $(this).find('.form-control').each(function (subjectIndex) {
            const subjectName = $(this).val() || `-`;
            daySubjects.push({ [`Predmet ${subjectIndex}`]: subjectName });
        });

        subjects.push({ [dayName]: daySubjects });
    });

    const jsonData = {
        subjects: subjects,
    };

    return JSON.stringify(jsonData, null, 2);
}
// ...

// ...
const fetchAllSchoolsData = async () => {
    try {
        const { data: schools, error } = await _supabase.from('schools').select('*');

        if (error) {
            throw error;
        }

        const fuse = initializeFuse(schools);

        const searchInput = document.getElementById('searchInput');
        const classInfo = document.getElementById('classInfo');
        const autocompleteList = document.getElementById('autocompleteList');

        searchInput.addEventListener('input', handleSearchInput(fuse, autocompleteList, schools));

        document.getElementById('searchButton').addEventListener('click', function () {

            const searchValue = searchInput.value.trim();
            const classInfoValue = classInfo.value.trim();

            if (!selectedSchool) {
                showErrorMessage('Nije odabrana škola.');
                return;
            }

            if (!classInfoValue) {
                showErrorMessage('Nije odabran razred i smjer.');
                return;
            }

            if (searchValue === '' || classInfoValue === '') {
                showErrorMessage('Molimo vas da popunite sva polja.');
                return;
            }

            if (selectedSchool) {
                selectedSchool = {
                    id: selectedSchool.id,
                    naziv: selectedSchool.naziv + ' - ' + selectedSchool.mjesto,
                    mjesto: selectedSchool.mjesto
                };
            } else {
                showErrorMessage('Nije odabrana škola.');
            }

            const jsonData = generateJsonData();

            addNewSchedule(selectedSchool.id, selectedSchool.naziv, selectedSchool.mjesto, classInfoValue, jsonData);
        });


    } catch (error) {
        console.error('Error fetching schools data:', error.message);
    }
};

// Dodatak za rad sa rasporedom
$(document).ready(function () {
    // Početno dodavanje redova za dane
    for (let i = 0; i < 7; i++) {
        addRow(i);
    }

    // Funkcija za dodavanje reda za dan
    function addRow(dayIndex) {
        let day = ['Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota', 'Nedjelja'][dayIndex];

        let row = `<tr>
                    <td>${day}</td>
                    <td>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Predmet 0 (Predsat)">
                        <button class="btn btn-outline-secondary" type="button" onclick="addSubject(${dayIndex})">Dodaj</button>
                      </div>
                    </td>
                  </tr>`;

        $("#rasporedBody").append(row);
    }

    // Funkcija za dodavanje novog input polja za predmet
    window.addSubject = function (dayIndex) {
        let input = `<div class="input-group mb-3">
                     <input type="text" class="form-control" placeholder="Predmet ${$("#rasporedBody tr").eq(dayIndex).find('.input-group').length}">
                     <button class="btn btn-outline-secondary" type="button" onclick="deleteSubject(this)">Izbriši</button>
                   </div>`;

        $("#rasporedBody tr").eq(dayIndex).find('td:last-child .input-group').last().after(input);
    };

    // Funkcija za brisanje input polja za predmet
    window.deleteSubject = function (btn) {
        $(btn).closest('.input-group').remove();
    };
});

fetchAllSchoolsData();

// Function to display error messages
function showErrorMessage(message) {
    const errorMessageDiv = document.getElementById('response-message');

    errorMessageDiv.textContent = message;
    errorMessageDiv.classList.remove('d-none');
}

// ...

function addNewSchedule(id, name, place, classInfo, schedule) {
    const user = parseJwt(sessionStorage.getItem("token"));
    const token = sessionStorage.getItem("token");

    const url = `http://192.168.1.106/api/v1/new-schedule/${id}/${name}/${place}/${classInfo}/${schedule}/${token}`;

    // Slanje GET zahtjeva s Axios
    axios.get(url)
        .then(response => {
            if (response.data && response.data.error) {
                showErrorMessage(response.data.error);
            } else {
                if (response.status === 200) {
                    window.location.href = '/';
                }
            }
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.error) {
                showErrorMessage(error.response.data.error);
            } else {
                showErrorMessage('Došlo je do greške prilikom obrade zahtjeva.');
            }
        });
}

// ...

