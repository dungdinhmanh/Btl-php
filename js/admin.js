const adminBody = document.querySelector(".admin-body");
const sidebarToggle = document.querySelector(".admin-sidebar-toggle");
const sidebarStorageKey = "tnc-admin-sidebar-collapsed";

function setSidebarCollapsed(collapsed) {
	adminBody.classList.toggle("sidebar-collapsed", collapsed);
	sidebarToggle.setAttribute("aria-expanded", String(!collapsed));
	sidebarToggle.setAttribute("aria-label", collapsed ? "Mở rộng thanh bên" : "Thu gọn thanh bên");
	sidebarToggle.querySelector("span").textContent = collapsed ? "Mở rộng" : "Thu gọn";
	sidebarToggle.querySelector("i").className = collapsed
		? "bi bi-layout-sidebar"
		: "bi bi-layout-sidebar-inset";
}

setSidebarCollapsed(localStorage.getItem(sidebarStorageKey) === "true");

sidebarToggle.addEventListener("click", () => {
	const collapsed = !adminBody.classList.contains("sidebar-collapsed");
	setSidebarCollapsed(collapsed);
	localStorage.setItem(sidebarStorageKey, String(collapsed));
});
