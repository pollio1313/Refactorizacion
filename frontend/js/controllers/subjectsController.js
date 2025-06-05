import { subjectsAPI } from '../api/subjectsAPI.js';

document.addEventListener('DOMContentLoaded', () => 
{
    loadSubjects();
    setupSubjectFormHandler();
});

function setupSubjectFormHandler() 
{
  const form = document.getElementById('subjectForm');
  form.addEventListener('submit', async e => 
  {
        e.preventDefault();
        const subject = 
        {
            id: document.getElementById('subjectId').value.trim(),
            name: document.getElementById('name').value.trim()
        };

        try 
        {
            if (subject.id)                                                         //cambie aca para vea si se duplica
            {
                await subjectsAPI.update(subject);
            }
            else
            {
                const existingSubjects = await subjectsAPI.fetchAll();
                const duplicate = existingSubjects.some(s => s.name.toLowerCase() === subject.name.toLowerCase());

                if (duplicate) {
                    showSubjectMessage('No es posible agregar dos materias con el mismo nombre.', true);
                    return;
                }

                await subjectsAPI.create(subject);
            }
                        
            form.reset();
            document.getElementById('subjectId').value = '';
            loadSubjects();
        }
        catch (err)
        {
            console.error(err.message);
        }
  });
}

async function loadSubjects()
{
    try
    {
        const subjects = await subjectsAPI.fetchAll();
        renderSubjectTable(subjects);
    }
    catch (err)
    {
        console.error('Error cargando materias:', err.message);
    }
}
//aca es la funcion apra mostrar el error

function showSubjectMessage(msg, isError = true, duration = 2000) {
    const msgDiv = document.getElementById('subjectMessage');
    msgDiv.textContent = msg;
    msgDiv.style.display = 'block';
    msgDiv.className = isError ? 'w3-text-red w3-margin-bottom' : 'w3-text-green w3-margin-bottom';

    if (duration > 0) {
        setTimeout(() => {
            msgDiv.style.display = 'none';
            msgDiv.textContent = '';
        }, duration);
    }
}

function renderSubjectTable(subjects)
{
    const tbody = document.getElementById('subjectTableBody');
    tbody.replaceChildren();

    subjects.forEach(subject =>
    {
        const tr = document.createElement('tr');

        tr.appendChild(createCell(subject.name));
        tr.appendChild(createSubjectActionsCell(subject));

        tbody.appendChild(tr);
    });
}

function createCell(text)
{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}

function createSubjectActionsCell(subject)
{
    const td = document.createElement('td');

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => 
    {
        document.getElementById('subjectId').value = subject.id;
        document.getElementById('name').value = subject.name;
    });

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDeleteSubject(subject.id));

    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}

async function confirmDeleteSubject(id)                 //cambios apra  qno borre si tiene un estudainte
{
    if (!confirm('¿Seguro que deseas borrar esta materia?')) return;

    try
    {
        await subjectsAPI.remove(id);
        loadSubjects();
    }
    catch (err)
    {
        try {
            const response = await err.response.json();
            showSubjectMessage(response.error || "Error al borrar materia.", true);
        } catch {
            showSubjectMessage("Error inesperado al borrar materia.", true);
        }
    }
}