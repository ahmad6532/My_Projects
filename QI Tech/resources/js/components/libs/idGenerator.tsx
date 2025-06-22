import { FormPage } from "../FormElements";

export default function idGenerator(pages: FormPage[]): string {
    const highestId = pages.reduce((maxId, page) => {
        const pageMaxId = page.questions.reduce((maxQuestionId, question) => {
            const match = question.id.match(/^question(\d+)$/);
            if (match) {
                const idNumber = parseInt(match[1], 10);
                return Math.max(maxQuestionId, idNumber);
            }
            return maxQuestionId;
        }, 0);
        return Math.max(maxId, pageMaxId);
    }, 0);
    const nextId = highestId + 1;
    return `question${nextId}`;
}

export function pageIdGenerator(elements:FormPage[]): string {
    const highestId = elements.reduce((maxId, element) => {
        const match = element.id.match(/^page(\d+)$/);
        if (match) {
            const idNumber = parseInt(match[1], 10);
            return Math.max(maxId, idNumber);
        }
        return maxId;
    }, 0);
    const nextId = highestId + 1;
    return `page${nextId}`;
}
