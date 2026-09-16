/**
 * Utility to parse CSV files and convert model names to URL slugs
 */

function toSlug(str) {
	if (!str) return '';
	return str
		.toString()
		.toLowerCase()
		.trim()
		.normalize('NFD')
		.replace(/[\u0300-\u036f]/g, '')
		.replace(/[đĐ]/g, 'd')
		.replace(/[^a-z0-9 -]/g, '')
		.replace(/\s+/g, '-')
		.replace(/-+/g, '-');
}

async function fetchAndParseCSV(csvPath) {
	try {
		const response = await fetch(csvPath);
		if (!response.ok) return [];
		const text = await response.text();
		return parseCSVText(text);
	} catch (err) {
		console.error('Lỗi tải CSV:', csvPath, err);
		return [];
	}
}

function parseCSVText(text) {
	const lines = text.split(/\r?\n/).map((l) => l.trim()).filter(Boolean);
	if (lines.length < 2) return [];
	const headers = parseCSVLine(lines[0]);
	return lines.slice(1).map((line) => {
		const values = parseCSVLine(line);
		return Object.fromEntries(headers.map((h, i) => [h.trim(), (values[i] || '').trim()]));
	});
}

function parseCSVLine(line) {
	// ponytail: naive quote handling (no escaped quotes inside fields), upgrade if CSVs contain "he said ""hi"""
	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map((m) => m[2] ?? m[3] ?? '');
}
