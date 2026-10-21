/**
 * Soạn thảo tin tức: contenteditable + execCommand (không cần viết HTML tay),
 * khung xem trước cập nhật trực tiếp và lưu qua backend/api/admin/news.php.
 * Nội dung lưu trong DB luôn là HTML ở dạng "chuẩn" (ảnh assets/... không có ../).
 */
const POST = window.NEWS_POST || {};

const newsForm = document.querySelector("#news-form");

if (newsForm) {
	const els = {
		title: document.querySelector("#news-title"),
		slug: document.querySelector("#news-slug"),
		category: document.querySelector("#news-category"),
		excerpt: document.querySelector("#news-excerpt"),
		status: document.querySelector("#news-status"),
		publishedAt: document.querySelector("#news-published-at"),
		cover: document.querySelector("#news-cover"),
		coverPreview: document.querySelector("#news-cover-preview"),
		coverThumb: document.querySelector("#news-cover-thumb"),
		coverFile: document.querySelector("#news-cover-file"),
		coverUploadBtn: document.querySelector("#news-cover-upload-btn"),
		coverRemove: document.querySelector("#news-cover-remove"),
		editor: document.querySelector("#news-editor"),
		source: document.querySelector("#news-source"),
		toolbar: document.querySelector("#news-toolbar"),
		blockSelect: document.querySelector("#news-block"),
		textColor: document.querySelector("#news-text-color"),
		highlightColor: document.querySelector("#news-highlight-color"),
		saveBtn: document.querySelector("#news-save-btn"),
		previewTitle: document.querySelector("#preview-title"),
		previewDate: document.querySelector("#preview-date"),
		previewContent: document.querySelector("#preview-content"),
		previewEmpty: document.querySelector("#news-preview-empty"),
		previewViews: document.querySelector("#preview-views"),
		preview: document.querySelector("#news-preview"),
		previewTabs: Array.from(document.querySelectorAll("[data-preview-tab]")),
		previewPanes: Array.from(document.querySelectorAll("[data-preview-pane]")),
		previewListCover: document.querySelector("#preview-list-cover"),
		previewListCategory: document.querySelector("#preview-list-category"),
		previewListDate: document.querySelector("#preview-list-date"),
		previewListTitle: document.querySelector("#preview-list-title"),
		previewListExcerpt: document.querySelector("#preview-list-excerpt"),
		viewLive: document.querySelector("#news-view-live"),
	};

	const linkModal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#newsLinkModal"));
	const imageModal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#newsImageModal"));
	const videoModal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#newsVideoModal"));

	let savedRange = null;
	let sourceMode = false;
	let dirty = false;

	/* ------------------------------------------------------------------ */
	/* Chuẩn hoá đường dẫn ảnh: DB lưu "assets/..." còn trang /admin/ cần "../" */

	const displaySrc = (src) => {
		const value = String(src || "");
		if (value === "" || value.startsWith("../") || /^(https?:|data:|blob:|\/)/i.test(value)) {
			return value;
		}
		return `../${value}`;
	};

	const canonicalSrc = (src) => String(src || "").replace(/^\.\.\//, "");

	function applyDisplaySrcs(root) {
		root.querySelectorAll("img[src]").forEach((img) => {
			img.setAttribute("src", displaySrc(img.getAttribute("src")));
		});
	}

	/* ------------------------------------------------------------------ */
	/* Dọn HTML khi dán từ nguồn ngoài (Word, web khác) */

	const ALLOWED_TAGS = new Set([
		"P", "BR", "STRONG", "B", "EM", "I", "U", "S", "STRIKE", "DEL", "INS",
		"H2", "H3", "H4", "UL", "OL", "LI", "A", "IMG", "IFRAME", "BLOCKQUOTE",
		"HR", "DIV", "SPAN", "FIGURE", "FIGCAPTION", "SUB", "SUP",
	]);
	const DROP_TAGS = new Set([
		"SCRIPT", "STYLE", "LINK", "META", "TITLE", "NOSCRIPT", "TEMPLATE", "OBJECT",
		"EMBED", "FORM", "INPUT", "BUTTON", "SELECT", "TEXTAREA", "VIDEO", "AUDIO",
		"SOURCE", "SVG", "MATH", "CANVAS", "MAP", "AREA", "BASE",
	]);
	const ALLOWED_ATTRS = {
		A: ["href", "target", "rel"],
		IMG: ["src", "alt", "width", "height"],
		IFRAME: ["src", "width", "height", "allow", "allowfullscreen", "frameborder", "title", "referrerpolicy"],
		P: ["align"],
		H2: ["align"],
		H3: ["align"],
		H4: ["align"],
		DIV: ["align"],
	};

	/* Inline style: chỉ giữ các thuộc tính trình bày an toàn, chặn url()/JS. */
	const SAFE_STYLE_PROPS = [
		"color", "background-color", "font-family", "font-size", "font-weight",
		"font-style", "font-variant", "text-decoration", "text-align", "line-height",
		"letter-spacing", "margin-top", "margin-right", "margin-bottom", "margin-left",
		"padding-top", "padding-right", "padding-bottom", "padding-left",
		"padding-inline-start", "list-style-type", "vertical-align", "white-space",
	];
	const UNSAFE_STYLE_VALUE = /url\s*\(|expression\s*\(|javascript\s*:|@import|var\s*\(/i;

	function sanitizeInlineStyle(value) {
		const probe = document.createElement("span");
		probe.setAttribute("style", String(value || ""));
		const declarations = [];
		SAFE_STYLE_PROPS.forEach((property) => {
			const propertyValue = probe.style.getPropertyValue(property).trim();
			if (!propertyValue || UNSAFE_STYLE_VALUE.test(propertyValue)) return;
			if (typeof CSS !== "undefined" && !CSS.supports(property, propertyValue)) return;
			declarations.push(`${property}: ${propertyValue}`);
		});
		return declarations.join("; ");
	}

	/** Chuẩn hoá link YouTube/Vimeo thành URL nhúng; null nếu không hợp lệ. */
	function videoEmbedUrl(value) {
		const raw = String(value || "").trim();
		if (!raw) return null;

		let url;
		try {
			url = new URL(raw, "https://www.youtube.com");
		} catch {
			return null;
		}

		const host = url.hostname.replace(/^www\./i, "").toLowerCase();
		if (host === "youtu.be") {
			const id = url.pathname.split("/").filter(Boolean)[0] || "";
			return /^[\w-]{6,}$/.test(id) ? `https://www.youtube.com/embed/${id}` : null;
		}
		if (["youtube.com", "m.youtube.com", "music.youtube.com", "youtube-nocookie.com"].includes(host)) {
			const id =
				url.searchParams.get("v") ||
				(url.pathname.match(/^\/(?:embed|shorts|live)\/([\w-]{6,})/) || [])[1] ||
				"";
			return /^[\w-]{6,}$/.test(id) ? `https://www.youtube.com/embed/${id}` : null;
		}
		if (host === "vimeo.com") {
			const id = url.pathname.split("/").filter(Boolean).pop() || "";
			if (!/^\d+$/.test(id)) return null;
			const hash = url.searchParams.get("h");
			return `https://player.vimeo.com/video/${id}${hash ? `?h=${encodeURIComponent(hash)}` : ""}`;
		}
		if (host === "player.vimeo.com") {
			return /^\/video\/\d+/.test(url.pathname) ? url.href : null;
		}

		return null;
	}

	function sanitizeHtml(html) {
		const template = document.createElement("template");
		template.innerHTML = String(html || "");
		sanitizeNode(template.content);
		return template.innerHTML;
	}

	function sanitizeNode(node) {
		[...node.childNodes].forEach((child) => {
			if (child.nodeType === 8) {
				child.remove();
				return;
			}
			if (child.nodeType !== 1) {
				return;
			}

			const tag = child.tagName;
			if (DROP_TAGS.has(tag)) {
				child.remove();
				return;
			}
			if (!ALLOWED_TAGS.has(tag)) {
				sanitizeNode(child);
				const fragment = document.createDocumentFragment();
				while (child.firstChild) {
					fragment.appendChild(child.firstChild);
				}
				child.replaceWith(fragment);
				return;
			}

			[...child.attributes].forEach((attribute) => {
				const name = attribute.name.toLowerCase();
				if (name === "style") {
					const cleaned = sanitizeInlineStyle(attribute.value);
					if (cleaned) {
						child.setAttribute("style", cleaned);
					} else {
						child.removeAttribute("style");
					}
					return;
				}
				if (!(ALLOWED_ATTRS[tag] || []).includes(name)) {
					child.removeAttribute(attribute.name);
					return;
				}
				if (name === "href" && /^\s*(javascript|vbscript|data):/i.test(attribute.value)) {
					child.removeAttribute("href");
				}
				if (name === "src" && /^\s*(javascript|vbscript):/i.test(attribute.value)) {
					child.removeAttribute("src");
				}
			});

			if (tag === "IFRAME") {
				const embed = videoEmbedUrl(child.getAttribute("src"));
				if (!embed) {
					child.remove();
					return;
				}
				child.setAttribute("src", embed);
			}
			if (tag === "IMG" && !(child.getAttribute("src") || "").trim()) {
				child.remove();
				return;
			}

			sanitizeNode(child);
		});
	}

	/* ------------------------------------------------------------------ */
	/* Vùng soạn thảo */

	document.execCommand("styleWithCSS", false, false);
	document.execCommand("defaultParagraphSeparator", false, "p");

	const escapeHtml = (value) =>
		String(value ?? "").replace(/[&<>'"]/g, (character) =>
			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
		);

	function updatePlaceholder() {
		const empty =
			els.editor.textContent.trim() === "" && !els.editor.querySelector("img, iframe, hr");
		els.editor.dataset.empty = empty ? "true" : "false";
	}

	function saveSelection() {
		const selection = window.getSelection();
		if (!selection || selection.rangeCount === 0) return;
		const range = selection.getRangeAt(0);
		if (els.editor.contains(range.commonAncestorContainer)) {
			savedRange = range.cloneRange();
		}
	}

	function restoreSelection() {
		if (!savedRange) return;
		const selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(savedRange);
	}

	/** Nếu chưa có vùng chọn (vd. bấm nút Dán khi editor chưa focus), đặt con trỏ cuối editor. */
	function ensureEditorSelection() {
		if (document.activeElement !== els.editor) els.editor.focus();
		if (savedRange) return;
		const range = document.createRange();
		range.selectNodeContents(els.editor);
		range.collapse(false);
		const selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
		savedRange = range.cloneRange();
	}

	function markDirty() {
		dirty = true;
	}

	let previewTimer = null;
	function schedulePreview() {
		clearTimeout(previewTimer);
		previewTimer = setTimeout(updatePreview, 180);
	}

	/* ------------------------------------------------------------------ */
	/* Xem trước */

	function pad(value) {
		return String(value).padStart(2, "0");
	}

	function formatDateTime(value) {
		if (!value) {
			const now = new Date();
			return `${pad(now.getDate())}/${pad(now.getMonth() + 1)}/${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}`;
		}
		const [datePart, timePart] = String(value).split("T");
		const [year, month, day] = (datePart || "").split("-");
		return `${day}/${month}/${year} ${timePart || "00:00"}`;
	}

	function formatDate(value) {
		return formatDateTime(value).split(" ")[0];
	}

	/** Giờ địa phương của máy quản trị, dùng khi đăng ngay để không phụ thuộc timezone của PHP. */
	function localDateTimeValue(date = new Date()) {
		return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
	}

	function updatePreview() {
		const title = els.title.value.trim() || "Tiêu đề bài viết";
		els.previewTitle.textContent = title;
		els.previewDate.textContent = formatDateTime(els.publishedAt.value);

		const excerpt = els.excerpt.value.trim();
		const cover = els.cover.value.trim();

		// Card ngoài trang tin (news.php) dùng ảnh bìa hoặc logo mặc định như newsCover().
		// Tab "Trong bài viết" chỉ có tiêu đề, ngày giờ, lượt xem và nội dung; ảnh bìa/mô tả
		// ngắn thuộc card ngoài, đúng cấu trúc trang chi tiết của TNC Store.
		const categoryOption = els.category.options[els.category.selectedIndex];
		els.previewListTitle.textContent = title;
		els.previewListCategory.textContent =
			categoryOption && categoryOption.value ? categoryOption.textContent.trim() : "Danh mục";
		els.previewListDate.textContent = formatDate(els.publishedAt.value);
		els.previewListExcerpt.textContent = excerpt;
		els.previewListCover.src = displaySrc(cover || "assets/img/branding/tnc.png");
		els.previewListCover.alt = title;

		const raw = sourceMode ? els.source.value : els.editor.innerHTML;
		const holder = document.createElement("div");
		holder.innerHTML = sanitizeHtml(raw);
		applyDisplaySrcs(holder);
		els.previewContent.innerHTML = holder.innerHTML;

		const empty =
			els.previewContent.textContent.trim() === "" &&
			!els.previewContent.querySelector("img, iframe, hr");
		els.previewEmpty.hidden = !empty;
	}

	function setPreviewTab(name) {
		els.previewTabs.forEach((tab) => {
			const active = tab.dataset.previewTab === name;
			tab.classList.toggle("active", active);
			tab.setAttribute("aria-selected", active ? "true" : "false");
			tab.tabIndex = active ? 0 : -1;
		});
		els.previewPanes.forEach((pane) => {
			pane.hidden = pane.dataset.previewPane !== name;
		});
		if (els.preview) els.preview.scrollTop = 0;
	}

	els.previewTabs.forEach((tab) => {
		tab.addEventListener("click", () => setPreviewTab(tab.dataset.previewTab));
		tab.addEventListener("keydown", (event) => {
			if (event.key !== "ArrowLeft" && event.key !== "ArrowRight") return;
			event.preventDefault();
			const index = els.previewTabs.indexOf(tab);
			const step = event.key === "ArrowRight" ? 1 : -1;
			const next = els.previewTabs[(index + step + els.previewTabs.length) % els.previewTabs.length];
			next.focus();
			setPreviewTab(next.dataset.previewTab);
		});
	});

	if (els.preview) {
		// Preview chỉ để xem, không cho link bên trong điều hướng khỏi trang soạn thảo.
		els.preview.addEventListener("click", (event) => {
			if (event.target.closest("a")) event.preventDefault();
		});
	}

	function updateLiveLink() {
		const slug = els.slug.value.trim();
		if (POST.id && slug && els.status.value === "published") {
			els.viewLive.href = `../news-post.php?slug=${encodeURIComponent(slug)}`;
			els.viewLive.hidden = false;
		} else {
			els.viewLive.hidden = true;
		}
	}

	/* ------------------------------------------------------------------ */
	/* Toolbar */

	const STATE_COMMANDS = [
		"bold", "italic", "underline", "strikeThrough",
		"insertUnorderedList", "insertOrderedList",
		"justifyLeft", "justifyCenter", "justifyRight", "justifyFull",
	];

	function refreshToolbar() {
		if (sourceMode) return;
		STATE_COMMANDS.forEach((command) => {
			const button = els.toolbar.querySelector(`[data-cmd="${command}"]`);
			if (!button) return;
			let active = false;
			try {
				active = document.queryCommandState(command);
			} catch {
				active = false;
			}
			button.classList.toggle("active", active);
		});

		let block = "p";
		try {
			const value = String(document.queryCommandValue("formatBlock") || "").toLowerCase();
			if (["h2", "h3", "h4", "blockquote"].includes(value)) {
				block = value;
			}
		} catch {
			block = "p";
		}
		els.blockSelect.value = block;
	}

	document.addEventListener("selectionchange", () => {
		if (document.activeElement === els.editor) {
			saveSelection();
		}
		refreshToolbar();
	});

	els.toolbar.addEventListener("mousedown", (event) => {
		saveSelection(); // cả input màu cũng cần giữ vùng chọn
		if (event.target.closest("button")) {
			event.preventDefault();
		}
	});

	els.toolbar.addEventListener("click", (event) => {
		const button = event.target.closest("button");
		if (!button) return;
		const command = button.dataset.cmd;
		const action = button.dataset.action;

		if (command) {
			els.editor.focus();
			restoreSelection();
			try {
				document.execCommand(command, false, null);
			} catch {
				/* trình duyệt không hỗ trợ lệnh này */
			}
			markDirty();
			updatePlaceholder();
			schedulePreview();
			return;
		}

		if (action === "link") openLinkModal();
		if (action === "image") openImageModal();
		if (action === "video") openVideoModal();
		if (action === "paste") pasteFromClipboard();
		if (action === "clear-format") {
			els.editor.focus();
			restoreSelection();
			document.execCommand("removeFormat", false, null);
			document.execCommand("formatBlock", false, "<p>");
			markDirty();
			schedulePreview();
		}
		if (action === "source") toggleSource();
	});

	/** Màu chữ/nền: buộc styleWithCSS để ra <span style="color:..."> thay vì <font>. */
	function applyInlineColor(command, color) {
		if (sourceMode || !color) return;
		els.editor.focus();
		restoreSelection();
		try {
			document.execCommand("styleWithCSS", false, true);
			const applied = document.execCommand(command, false, color);
			if (!applied && command === "hiliteColor") {
				document.execCommand("backColor", false, color);
			}
		} finally {
			document.execCommand("styleWithCSS", false, false);
		}
		markDirty();
		updatePlaceholder();
		schedulePreview();
	}

	if (els.textColor) {
		els.textColor.addEventListener("change", () => applyInlineColor("foreColor", els.textColor.value));
	}
	if (els.highlightColor) {
		els.highlightColor.addEventListener("change", () => applyInlineColor("hiliteColor", els.highlightColor.value));
	}

	els.blockSelect.addEventListener("change", () => {
		if (sourceMode) return;
		els.editor.focus();
		restoreSelection();
		document.execCommand("formatBlock", false, `<${els.blockSelect.value}>`);
		markDirty();
		schedulePreview();
	});

	els.editor.addEventListener("keydown", (event) => {
		if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === "k") {
			event.preventDefault();
			openLinkModal();
		}
	});

	function decodeHtmlEntities(value) {
		const textarea = document.createElement("textarea");
		textarea.innerHTML = String(value || "");
		return textarea.value;
	}

	function insertPastedContent(html, text) {
		let cleaned = html ? sanitizeHtml(html) : "";
		const decodedText = text ? decodeHtmlEntities(text) : "";

		// Mã nhúng iframe thường được clipboard trả về dạng text/plain đã escape.
		if (!/<iframe\b/i.test(cleaned) && /<\s*iframe\b/i.test(decodedText)) {
			cleaned = sanitizeHtml(decodedText);
		}

		if (cleaned.trim() !== "") {
			document.execCommand("insertHTML", false, cleaned);
			return;
		}
		if (text) {
			document.execCommand("insertHTML", false, escapeHtml(text).replace(/\r?\n/g, "<br>"));
		}
	}

	els.editor.addEventListener("paste", async (event) => {
		event.preventDefault();
		const clipboard = event.clipboardData;
		if (!clipboard) return;

		let imageFiles = Array.from(clipboard.items || [])
			.filter((item) => item.kind === "file" && item.type.startsWith("image/"))
			.map((item) => item.getAsFile())
			.filter(Boolean);
		if (!imageFiles.length) {
			imageFiles = Array.from(clipboard.files || []).filter((file) => file.type.startsWith("image/"));
		}
		if (imageFiles.length) {
			await insertPastedFiles(imageFiles);
			return;
		}

		insertPastedContent(clipboard.getData("text/html"), clipboard.getData("text/plain"));
		markDirty();
		updatePlaceholder();
		schedulePreview();
	});

	/** Tải ảnh dán từ clipboard lên assets/news rồi chèn vào đúng vị trí con trỏ. */
	async function insertPastedFiles(files) {
		if (!files.length) return;
		ensureEditorSelection();
		saveSelection();
		try {
			const urls = [];
			for (const file of files) {
				urls.push(await uploadImage(file));
			}
			els.editor.focus();
			restoreSelection();
			const html = urls
				.map((url) => `<img src="${escapeHtml(displaySrc(url))}" alt="">`)
				.join("");
			document.execCommand("insertHTML", false, html);
			saveSelection();
			markDirty();
			updatePlaceholder();
			schedulePreview();
		} catch (error) {
			alert(error.message || "Không tải được ảnh dán vào.");
		}
	}

	/** Nút Dán: đọc clipboard hiện đại; nếu trình duyệt chặn thì hướng dẫn Ctrl+V. */
	async function pasteFromClipboard() {
		if (sourceMode) {
			alert("Chuyển về chế độ soạn thảo để dán nội dung.");
			return;
		}
		if (!navigator.clipboard || typeof navigator.clipboard.read !== "function") {
			alert("Trình duyệt không hỗ trợ đọc clipboard. Hãy bấm vào vùng soạn thảo rồi nhấn Ctrl+V.");
			return;
		}

		try {
			const items = await navigator.clipboard.read();
			if (!items.length) {
				alert("Clipboard đang trống.");
				return;
			}
			ensureEditorSelection();
			for (const item of items) {
				const imageType = item.types.find((type) => type.startsWith("image/"));
				if (imageType) {
					const blob = await item.getType(imageType);
					const extension = imageType.split("/")[1] || "png";
					await insertPastedFiles([new File([blob], `clipboard.${extension}`, { type: imageType })]);
					continue;
				}
				const html = item.types.includes("text/html")
					? await (await item.getType("text/html")).text()
					: "";
				const text = item.types.includes("text/plain")
					? await (await item.getType("text/plain")).text()
					: "";
				els.editor.focus();
				restoreSelection();
				insertPastedContent(html, text);
				markDirty();
				updatePlaceholder();
				schedulePreview();
			}
		} catch (error) {
			alert("Không đọc được clipboard (trình duyệt có thể chặn quyền). Hãy nhấn Ctrl+V trong vùng soạn thảo.");
		}
	}

	/* ------------------------------------------------------------------ */
	/* Chèn liên kết */

	const linkText = document.querySelector("#news-link-text");
	const linkUrl = document.querySelector("#news-link-url");
	const linkBlank = document.querySelector("#news-link-blank");
	const linkConfirm = document.querySelector("#news-link-confirm");
	const linkModalTitle = document.querySelector("#newsLinkModalTitle");
	let linkAnchor = null;

	/** Chuẩn hoá href: thêm https:// khi thiếu scheme, chặn scheme nguy hiểm. */
	function normalizeLinkUrl(value) {
		const raw = String(value || "").trim();
		if (!raw) return "";
		if (/^\s*(javascript|vbscript|data):/i.test(raw)) return null;
		if (/^(https?:|mailto:|tel:)/i.test(raw)) return raw;
		if (raw.startsWith("//")) return `https:${raw}`;
		if (raw.startsWith("/") || raw.startsWith("#") || raw.startsWith("./") || raw.startsWith("../")) {
			return raw;
		}
		if (/^[a-z][a-z0-9+.-]*:/i.test(raw)) return null;

		return `https://${raw}`;
	}

	function anchorFromSelection() {
		const selection = window.getSelection();
		if (!selection || selection.rangeCount === 0) return null;
		let node = selection.getRangeAt(0).startContainer;
		if (node.nodeType === 3) node = node.parentElement;
		return node ? node.closest("a") : null;
	}

	function openLinkModal() {
		saveSelection();
		linkAnchor = anchorFromSelection();
		const selection = window.getSelection();
		if (linkAnchor) {
			linkText.value = linkAnchor.textContent.trim();
			linkUrl.value = linkAnchor.getAttribute("href") || "";
			linkBlank.checked = linkAnchor.target === "_blank";
			linkModalTitle.textContent = "Sửa liên kết";
			linkConfirm.textContent = "Cập nhật liên kết";
		} else {
			linkText.value = selection && selection.rangeCount > 0 ? selection.toString().trim() : "";
			linkUrl.value = "";
			linkBlank.checked = true;
			linkModalTitle.textContent = "Chèn liên kết";
			linkConfirm.textContent = "Chèn liên kết";
		}
		linkModal.show();
		setTimeout(() => linkUrl.focus(), 250);
	}

	linkConfirm.addEventListener("click", () => {
		const url = normalizeLinkUrl(linkUrl.value);
		if (!url) {
			alert("Nhập đường dẫn liên kết hợp lệ (http, https, mailto, tel hoặc đường dẫn nội bộ).");
			linkUrl.focus();
			return;
		}
		const text = linkText.value.trim();
		const blank = linkBlank.checked;

		if (linkAnchor) {
			linkAnchor.setAttribute("href", url);
			if (text) linkAnchor.textContent = text;
			if (blank) {
				linkAnchor.setAttribute("target", "_blank");
				linkAnchor.setAttribute("rel", "noopener noreferrer");
			} else {
				linkAnchor.removeAttribute("target");
				linkAnchor.removeAttribute("rel");
			}
		} else {
			els.editor.focus();
			restoreSelection();
			const attributes = blank ? ' target="_blank" rel="noopener noreferrer"' : "";
			const label = escapeHtml(text || url);
			document.execCommand("insertHTML", false, `<a href="${escapeHtml(url)}"${attributes}>${label}</a>&nbsp;`);
		}

		linkModal.hide();
		markDirty();
		updatePlaceholder();
		schedulePreview();
	});

	linkUrl.addEventListener("keydown", (event) => {
		if (event.key === "Enter") {
			event.preventDefault();
			linkConfirm.click();
		}
	});

	/* ------------------------------------------------------------------ */
	/* Tải ảnh lên */

	async function uploadImage(file) {
		const formData = new FormData();
		formData.append("file", file);
		const response = await fetch("../backend/api/admin/news-upload.php", {
			method: "POST",
			body: formData,
			headers: { Accept: "application/json" },
		});
		let payload = null;
		try {
			payload = await response.json();
		} catch {
			payload = null;
		}
		if (!response.ok || !payload || !payload.ok) {
			throw new Error((payload && payload.message) || "Không tải được ảnh lên.");
		}
		return payload.data.url;
	}

	/* ------------------------------------------------------------------ */
	/* Ảnh đại diện */

	function updateCoverPreview() {
		const value = els.cover.value.trim();
		if (value) {
			els.coverThumb.src = displaySrc(value);
			els.coverPreview.hidden = false;
		} else {
			els.coverThumb.removeAttribute("src");
			els.coverPreview.hidden = true;
		}
	}

	els.coverUploadBtn.addEventListener("click", () => els.coverFile.click());

	els.coverFile.addEventListener("change", async () => {
		const file = els.coverFile.files && els.coverFile.files[0];
		if (!file) return;
		try {
			els.cover.value = await uploadImage(file);
			updateCoverPreview();
			markDirty();
			updatePreview();
		} catch (error) {
			alert(error.message || "Không tải được ảnh lên.");
		} finally {
			els.coverFile.value = "";
		}
	});

	els.cover.addEventListener("input", () => {
		updateCoverPreview();
		schedulePreview();
	});

	els.coverRemove.addEventListener("click", () => {
		els.cover.value = "";
		updateCoverPreview();
		markDirty();
		updatePreview();
	});

	/* ------------------------------------------------------------------ */
	/* Chèn ảnh vào nội dung */

	const imageSrc = document.querySelector("#news-image-src");
	const imageAlt = document.querySelector("#news-image-alt");
	const imageFile = document.querySelector("#news-image-file");
	const imageUploadBtn = document.querySelector("#news-image-upload-btn");
	const imageConfirm = document.querySelector("#news-image-confirm");
	const imagePreview = document.querySelector("#news-image-preview");
	const imagePreviewImg = imagePreview.querySelector("img");

	function openImageModal() {
		saveSelection();
		imageSrc.value = "";
		imageAlt.value = "";
		imagePreview.hidden = true;
		imagePreviewImg.removeAttribute("src");
		imageModal.show();
		setTimeout(() => imageSrc.focus(), 250);
	}

	function updateImagePreview() {
		const value = imageSrc.value.trim();
		imagePreviewImg.src = value ? displaySrc(value) : "";
		imagePreview.hidden = value === "";
	}

	imageSrc.addEventListener("input", updateImagePreview);

	imageUploadBtn.addEventListener("click", () => imageFile.click());

	imageFile.addEventListener("change", async () => {
		const file = imageFile.files && imageFile.files[0];
		if (!file) return;
		try {
			imageSrc.value = await uploadImage(file);
			updateImagePreview();
		} catch (error) {
			alert(error.message || "Không tải được ảnh lên.");
		} finally {
			imageFile.value = "";
		}
	});

	imageConfirm.addEventListener("click", () => {
		const src = imageSrc.value.trim();
		if (!src) {
			alert("Chọn ảnh để chèn.");
			return;
		}
		const alt = imageAlt.value.trim();
		els.editor.focus();
		restoreSelection();
		document.execCommand(
			"insertHTML",
			false,
			`<img src="${escapeHtml(displaySrc(src))}" alt="${escapeHtml(alt)}">`,
		);
		imageModal.hide();
		markDirty();
		updatePlaceholder();
		schedulePreview();
	});

	/* ------------------------------------------------------------------ */
	/* Chèn video YouTube */

	const videoUrl = document.querySelector("#news-video-url");
	const videoConfirm = document.querySelector("#news-video-confirm");

	function openVideoModal() {
		saveSelection();
		videoUrl.value = "";
		videoModal.show();
		setTimeout(() => videoUrl.focus(), 250);
	}

	videoConfirm.addEventListener("click", () => {
		const embed = videoEmbedUrl(videoUrl.value.trim());
		if (!embed) {
			alert("Đường dẫn video YouTube/Vimeo không hợp lệ.");
			videoUrl.focus();
			return;
		}
		els.editor.focus();
		restoreSelection();
		document.execCommand(
			"insertHTML",
			false,
			`<iframe width="560" height="315" src="${embed}" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`,
		);
		videoModal.hide();
		markDirty();
		updatePlaceholder();
		schedulePreview();
	});

	videoUrl.addEventListener("keydown", (event) => {
		if (event.key === "Enter") {
			event.preventDefault();
			videoConfirm.click();
		}
	});

	/* ------------------------------------------------------------------ */
	/* Xem / sửa mã HTML */

	function canonicalContent() {
		if (sourceMode) {
			return sanitizeHtml(els.source.value);
		}
		const clone = els.editor.cloneNode(true);
		clone.querySelectorAll("img[src]").forEach((img) => {
			img.setAttribute("src", canonicalSrc(img.getAttribute("src")));
		});
		return sanitizeHtml(clone.innerHTML);
	}

	function toggleSource() {
		const button = els.toolbar.querySelector('[data-action="source"]');
		if (!sourceMode) {
			els.source.value = canonicalContent();
			els.source.hidden = false;
			els.editor.hidden = true;
			sourceMode = true;
			if (button) button.classList.add("active");
		} else {
			els.editor.innerHTML = sanitizeHtml(els.source.value);
			applyDisplaySrcs(els.editor);
			els.source.hidden = true;
			els.editor.hidden = false;
			sourceMode = false;
			if (button) button.classList.remove("active");
		}
		updatePlaceholder();
		updatePreview();
	}

	/* ------------------------------------------------------------------ */
	/* Trạng thái + lưu */

	function showSavedNote() {
		let note = document.querySelector("#news-saved-note");
		if (!note) {
			note = document.createElement("div");
			note.id = "news-saved-note";
			note.className = "alert alert-success";
			note.innerHTML = '<i class="bi bi-check-lg me-2"></i>Bài viết đã được lưu.';
			newsForm.prepend(note);
		}
		note.hidden = false;
		clearTimeout(note._hideTimer);
		note._hideTimer = setTimeout(() => {
			note.hidden = true;
		}, 3000);
	}

	function slugifyVietnamese(value) {
		return String(value)
			.toLowerCase()
			.normalize("NFD")
			.replace(/[\u0300-\u036f]/g, "")
			.replace(/đ/g, "d")
			.replace(/[^a-z0-9]+/g, "-")
			.replace(/^-+|-+$/g, "")
			.slice(0, 180);
	}

	let slugTouched = Boolean(POST.slug);

	newsForm.addEventListener("input", markDirty);

	els.title.addEventListener("input", () => {
		if (!slugTouched && !POST.id) {
			els.slug.value = slugifyVietnamese(els.title.value);
		}
		updateLiveLink();
		schedulePreview();
	});

	els.slug.addEventListener("input", () => {
		slugTouched = true;
		updateLiveLink();
	});

	els.excerpt.addEventListener("input", schedulePreview);
	els.category.addEventListener("change", schedulePreview);
	els.publishedAt.addEventListener("input", () => updatePreview());
	els.status.addEventListener("change", () => {
		updateLiveLink();
	});

	els.editor.addEventListener("input", () => {
		updatePlaceholder();
		schedulePreview();
	});

	window.addEventListener("beforeunload", (event) => {
		if (!dirty) return;
		event.preventDefault();
		event.returnValue = "";
	});

	newsForm.addEventListener("submit", async (event) => {
		event.preventDefault();

		const title = els.title.value.trim();
		if (!title) {
			alert("Tiêu đề bài viết không được để trống.");
			els.title.focus();
			return;
		}
		const categoryId = Number(els.category.value || 0);
		if (!categoryId) {
			alert("Chọn danh mục cho bài viết.");
			els.category.focus();
			return;
		}

		const content = canonicalContent();
		const status = els.status.value;
		const contentEmpty =
			content.replace(/<[^>]*>/g, "").trim() === "" && !/<(img|iframe|hr)\b/i.test(content);
		if (status === "published" && contentEmpty) {
			if (!window.confirm("Bài viết chưa có nội dung. Vẫn đăng bài?")) {
				return;
			}
		}

		const publishedAt =
			els.publishedAt.value || (status === "published" ? localDateTimeValue() : "");

		const payload = {
			id: POST.id || undefined,
			title: title,
			slug: els.slug.value.trim(),
			category_id: categoryId,
			excerpt: els.excerpt.value.trim(),
			cover_image_path: els.cover.value.trim(),
			post_status: status,
			published_at: publishedAt,
			content: content,
		};

		els.saveBtn.disabled = true;
		try {
			const response = await fetch("../backend/api/admin/news.php", {
				method: POST.id ? "PUT" : "POST",
				headers: { "Content-Type": "application/json", Accept: "application/json" },
				body: JSON.stringify(payload),
			});
			const result = await response.json();
			if (!response.ok || !result.ok) {
				throw new Error(result.message || "Không lưu được bài viết.");
			}

			dirty = false;

			if (!POST.id) {
				window.location.href = `news-edit.php?id=${result.data.id}&saved=1`;
				return;
			}

			// Slug có thể bị thêm hậu tố -2 khi trùng; đồng bộ lại giá trị thật.
			if (result.data && result.data.slug) {
				els.slug.value = result.data.slug;
				POST.slug = result.data.slug;
			}
			updateLiveLink();
			showSavedNote();
		} catch (error) {
			alert(error.message || "Có lỗi xảy ra khi lưu bài viết.");
		} finally {
			els.saveBtn.disabled = false;
		}
	});

	/* ------------------------------------------------------------------ */
	/* Khởi tạo */

	els.title.value = POST.title || "";
	els.slug.value = POST.slug || "";
	els.category.value = POST.categoryId ? String(POST.categoryId) : "";
	els.excerpt.value = POST.excerpt || "";
	els.status.value = POST.status || "draft";
	els.publishedAt.value = POST.publishedInput || "";
	els.cover.value = POST.coverImage || "";

	updateCoverPreview();

	if (els.previewViews) {
		els.previewViews.textContent = Number(POST.views || 0).toLocaleString("vi-VN");
	}

	els.editor.innerHTML = sanitizeHtml(POST.content || "");
	applyDisplaySrcs(els.editor);
	updatePlaceholder();

	updateLiveLink();
	updatePreview();
	setPreviewTab("article");
	refreshToolbar();
}
