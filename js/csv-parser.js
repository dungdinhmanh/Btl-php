/**
 * Utility to parse the catalog CSV files and convert product names to URL slugs.
 */

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

async function fetchAndParseCSV(csvPath, fallbackProducts = []) {
	try {
		const response = await fetch(csvPath);
		if (!response.ok) return fallbackProducts;
		const text = await response.text();
		const parsed = parseCSVText(text);
		return parsed.length > 0 ? parsed : fallbackProducts;
	} catch {
		return fallbackProducts;
	}
}

function parseCSVText(text) {
	const lines = text
		.split(/\r?\n/)
		.map((line) => line.trim())
		.filter(Boolean);
	if (lines.length < 2) return [];
	const headers = parseCSVLine(lines[0]);
	return lines.slice(1).map((line) => {
		const values = parseCSVLine(line);
		return Object.fromEntries(
			headers.map((header, index) => [header.trim(), (values[index] || "").trim()]),
		);
	});
}

function parseCSVLine(line) {
	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map(
		(match) => match[2] ?? match[3] ?? "",
	);
}
