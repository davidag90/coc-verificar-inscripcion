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
    jQuery(resultDiv).fadeOut(300, function () {
      resultDiv.classList.remove("alert-success");
      resultDiv.classList.add("alert-danger");
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-xmark me-3"></i> <div>Por favor, ingrese un DNI válido.</div>`;
      jQuery(resultDiv).fadeIn(300);
    });

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
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-check me-3"></i> <div>Inscripción encontrada a nombre de <strong>${statusInscripcion.nombre}</strong>.
                            Te esperamos el día del evento <strong>a partir de las 8 AM</strong> en el <strong>Hotel Quinto Centenario</strong> <em>(ex-Sheraton)</em>.</div>`;
    } else {
      resultDiv.classList.remove("alert-success");
      resultDiv.classList.add("alert-danger");
      resultDiv.innerHTML = `<i class="fa-solid fa-circle-xmark me-3"></i> <div>No se encontró inscripción con su DNI. Puede consultar por WhatsApp al
                            <a href="https://wa.me/5493512372986" target="_blank">+54 9 351 2372-986</a> o inscribirse personalmente el día del evento
                            a partir de las 8 AM.</div>`;
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
