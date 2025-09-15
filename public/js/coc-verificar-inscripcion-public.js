let inscriptos = null;

const getData = async () => {
  if (inscriptos) return inscriptos;

  try {
    const response = await fetch(coc_ajax_object.json_inscripciones);
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    inscriptos = await response.json();
    return inscriptos;
  } catch (error) {
    console.error("Error fetching JSON data:", error);
    return [];
  }
};

inscriptos = getData();

const form = document.getElementById("coc-verificar-inscripcion-form");
const resultDiv = document.getElementById("coc-verificar-inscripcion-result");
const searchInput = document.querySelector("#search-input > input");

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const dni = parseInt(form.dni.value.trim());

  if (!dni) {
    console.log("fade-start");
    jQuery(resultDiv).fadeOut(300, function () {
      resultDiv.classList.remove("alert-success");
      resultDiv.classList.add("alert-danger");
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-xmark me-2"></i> <div>Por favor, ingrese un DNI válido.</div>`;
      console.error("No DNI");
      jQuery(resultDiv).fadeIn(300);
    });
    console.log("fade-end");

    return;
  }

  const inscriptosData = await inscriptos;

  const statusInscripcion = inscriptosData.find(
    (inscripto) => inscripto.dni === dni
  );

  jQuery(resultDiv).fadeOut(300, function () {
    if (statusInscripcion) {
      resultDiv.classList.remove("alert-danger");
      resultDiv.classList.add("alert-success");
      console.log("Good DNI");
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> <div>Inscripción encontrada a nombre de ${statusInscripcion.nombre}</div>`;
    } else {
      resultDiv.classList.remove("alert-success");
      resultDiv.classList.add("alert-danger");
      console.error("Bad DNI");
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-xmark me-2"></i> <div>No se encontró inscripción para el DNI ingresado.</div>`;
    }

    jQuery(resultDiv).fadeIn(300);
  });
});

searchInput.addEventListener("input", (e) => {
  const originalString = e.target.value;
  const maxLength = 8;
  const dni = originalString.replace(/\D/g, "").slice(0, maxLength);

  e.target.value = dni;
});
