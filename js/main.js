const addButton = document.getElementById('add-button');
const popupScreen = document.getElementById('popup-screen');
const projectTitleInput = document.getElementById('project-title');
const createProjectButton = document.getElementById('create-project');
const cancelButton = document.getElementById('cancel');

addButton.addEventListener('click', () => {
  popupScreen.classList.add('show');
});

cancelButton.addEventListener('click', () => {
  popupScreen.classList.remove('show');
});

createProjectButton.addEventListener('click', () => {
  const projectTitle = projectTitleInput.value.trim();
  if (projectTitle) {
    // Create the new project with the given title
    console.log(`Create new project: ${projectTitle}`);
    // Add the new project to the page (e.g., append to a list)
    // ...
    popupScreen.classList.remove('show');
  } else {
    alert('Please enter a project title');
  }
});