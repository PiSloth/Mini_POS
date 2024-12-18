import { initFlowbite } from "flowbite";
import "./bootstrap";
import "flowbite";
// import Alpine from 'alpinejs';

// import Alpine from "alpinejs";
// window.Alpine = Alpine;

// Alpine.start();
//flowbite init when navigate

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});

//alpine js components
// import component from "alpinejs-component";
// window.xComponent = {
//     name: "a-component",
// };

// Alpine.plugin(component);
