import { v4 as uuidv4 } from "uuid";
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

if (document.querySelector(".opo-webhookform-form")) {
    document.querySelectorAll(".opo-webhookform-form").forEach((form) => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            let loader = createLoader()
            let response = await sendFormdata(form);
            if (response.success === true) {
                nextStep(form);
            } else {
                console.log(response);
                let notyf = new Notyf({
                    duration: 9000,
                    position: {
                        x: "left",
                        y: "bottom",
                    },
                    dismissible: true
                });
                if (response.custom_msg) {
                    notyf.error(response.message);
                } else {
                    notyf.error(`Da scheint etwas schief gelaufen zu sein. Bitte versuche es später erneut. (${response.message})`);
                }
            }
            removeLoader(loader)
        });
    });
}

function createLoader() {
    let id = uuidv4();
    let loader = document.createElement("div");
    loader.setAttribute("id", "opo-loader" + id);
    loader.classList.add("opo-loader", "fixed", "top-0", "left-0", "w-full", "h-full", "flex", "items-center", "justify-center", "bg-black", "bg-opacity-50", "z-50", "text-white", "text-2xl", "font-bold", "backdrop-filter", "backdrop-blur-sm", "opacity-0", "transition-opacity", "duration-500");
    setTimeout(() => {
        loader.classList.add("opacity-100");
    }, 100);
    loader.innerHTML = `<p>Loading...</p>`;
    document.body.appendChild(loader);
    return id;
}

function removeLoader(id) {
    let loader = document.querySelector("#opo-loader" + id);
    loader.classList.remove("opacity-100");
    loader.classList.add("opacity-0");
    setTimeout(() => {
        loader.remove();
    }, 500);
}

async function sendFormdata(form) {
    let currentData = JSON.parse(form.closest(".opo-webhook-form-wrapper").getAttribute("data-formdata"))
    let formData = new FormData(form);
    let data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    data = { ...currentData, ...data };
    let url = form.getAttribute("action");
    let method = form.getAttribute("method");
    let headers = {
        "Content-Type": "application/json",
    };
    let body = JSON.stringify(data);
    let response = await fetch(url, {
        method,
        headers,
        body,
    });
    form.closest(".opo-webhook-form-wrapper").setAttribute("data-formdata", JSON.stringify(data));
    return response.json();
}

function nextStep(form) {
    let wrapper = form.closest(".opo-webhook-form-wrapper");
    let currentStep = wrapper.querySelector(".opo-webhookform-step:not([hidden])");
    let nextStep = currentStep.nextElementSibling;
    currentStep.setAttribute("hidden", "");
    nextStep.removeAttribute("hidden");
}