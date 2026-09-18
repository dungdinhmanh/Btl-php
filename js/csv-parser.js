/**
 * Utility to parse CSV files and convert model names to URL slugs
 */

const FALLBACK_PRODUCTS = [
	{
		Hãng: "Intel",
		Model: "Core i5-12400F",
		Socket: "LGA1700",
		"Số nhân/luồng": "6 nhân 12 luồng",
		"Xung nhịp": "Up to 4.4 GHz",
		TDP: "65W",
		iGPU: "Không có",
		"Giá TB (VNĐ)": "3.200.000 đ",
		Ảnh: "12400F-1.webp|12400F-2.webp",
	},
	{
		Hãng: "Intel",
		Model: "Core i7-14700K",
		Socket: "LGA1700",
		"Số nhân/luồng": "20 nhân 28 luồng",
		"Xung nhịp": "Up to 5.6 GHz",
		TDP: "125W",
		iGPU: "Intel UHD 770",
		"Giá TB (VNĐ)": "10.500.000 đ",
		Ảnh: "14700K-1.webp|14700K-2.webp",
	},
	{
		Hãng: "Intel",
		Model: "Core Ultra 7 265K",
		Socket: "LGA1851",
		"Số nhân/luồng": "20 nhân 20 luồng",
		"Xung nhịp": "Up to 5.5 GHz",
		TDP: "125W",
		iGPU: "Intel Graphics (4 Xe-cores)",
		"Giá TB (VNĐ)": "10.200.000 đ",
		Ảnh: "265K-1.jpg|265K-2.jpg",
	},
];

function toSlug(str) {
	if (!str) return "";
	return str
		.toString()
		.toLowerCase()
		.trim()
		.normalize("NFD")
		.replace(/[\u0300-\u036f]/g, "")
		.replace(/[đĐ]/g, "d")
		.replace(/[^a-z0-9 -]/g, "")
		.replace(/\s+/g, "-")
		.replace(/-+/g, "-");
}

async function fetchAndParseCSV(csvPath, fallbackProducts = FALLBACK_PRODUCTS) {
	try {
		const response = await fetch(csvPath);
		if (!response.ok) return fallbackProducts;
		const text = await response.text();
		const parsed = parseCSVText(text);
		return parsed.length > 0 ? parsed : fallbackProducts;
	} catch (err) {
		return fallbackProducts;
	}
}

function parseCSVText(text) {
	const lines = text
		.split(/\r?\n/)
		.map((l) => l.trim())
		.filter(Boolean);
	if (lines.length < 2) return [];
	const headers = parseCSVLine(lines[0]);
	return lines.slice(1).map((line) => {
		const values = parseCSVLine(line);
		return Object.fromEntries(headers.map((h, i) => [h.trim(), (values[i] || "").trim()]));
	});
}

function parseCSVLine(line) {
	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map((m) => m[2] ?? m[3] ?? "");
}
