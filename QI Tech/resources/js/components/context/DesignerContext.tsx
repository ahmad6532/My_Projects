import { createContext, useState } from "react";
import { FormElementInstance, FormPage } from "../FormElements";
import { pageIdGenerator } from "../libs/idGenerator";

type DesignerContextType = {
    elements: FormPage[];
    addElement: (
        pageId: string | null,
        index: number,
        element: FormElementInstance
    ) => void;
    setElements: React.Dispatch<React.SetStateAction<FormPage[]>>;
    removeElement: (pageId: string, id: string) => void;
    selectedItem: FormElementInstance | null;
    setSelectedItem: React.Dispatch<
        React.SetStateAction<FormElementInstance | null>
    >;
    updateElement: (
        pageId: string,
        id: string,
        element: FormElementInstance
    ) => void;
    openPreview: boolean;
    setOpenPreview: React.Dispatch<React.SetStateAction<boolean>>;
    selectedPageId: string;
    setSelectedPageId: React.Dispatch<React.SetStateAction<string>>;
    selectedPage: FormPage | null;
    setSelectedPage: React.Dispatch<React.SetStateAction<FormPage | null>>;
    removePage: (id: string) => void;
};

export const DesignerContext = createContext<DesignerContextType | null>(null);

export default function DesignerContextProvider({
    children,
}: {
    children: React.ReactNode;
}) {
    const [elements, setElements] = useState<FormPage[]>([]);
    const [selectedItem, setSelectedItem] =
        useState<FormElementInstance | null>(null);
    const [selectedPageId, setSelectedPageId] = useState<string>("");
    const [openPreview, setOpenPreview] = useState<boolean>(false);
    const [selectedPage, setSelectedPage] = useState<FormPage | null>(null);
    const addElement = (
        pageId: string | null,
        index: number,
        element: FormElementInstance
    ) => {
        if (pageId === null) {
            setElements((prevPages) => {
                const updatedPages = [...prevPages];
                const id = pageIdGenerator(updatedPages)
                updatedPages.splice(index, 0, {
                    id: id,
                    name: id,
                    description: "",
                    questions: [element],
                });
                return updatedPages;
            });
            return;
        }
        setElements((prevPages) => {
            const pageIndex = prevPages.findIndex((page) => page.id === pageId);
            if (pageIndex === -1) {
                console.error(`Page with id ${pageId} not found`);
                return prevPages;
            }
            const updatedPages = [...prevPages];
            const updatedPage = { ...updatedPages[pageIndex] };
            const updatedQuestions = [...updatedPage.questions];
            updatedQuestions.splice(index, 0, element);
            updatedPage.questions = updatedQuestions;
            updatedPages[pageIndex] = updatedPage;
            return updatedPages;
        });
    };
    const removeElement = (pageId: string, id: string) => {
        setElements((prevPages) => {
            const pageIndex = prevPages.findIndex((page) => page.id === pageId);
            if (pageIndex === -1) {
                console.error(`Page with id ${pageId} not found`);
                return prevPages;
            }
            const updatedPages = [...prevPages];
            const updatedPage = { ...updatedPages[pageIndex] };
            updatedPage.questions = updatedPage.questions.filter(
                (element) => element.id !== id
            );
            updatedPages[pageIndex] = updatedPage;
            return updatedPages;
        });
    };

    const updateElement = (
        pageId: string,
        id: string,
        element: FormElementInstance
    ) => {
        setElements((prev) => {
            const pageIndex = prev.findIndex((page) => page.id === pageId);
            if (pageIndex === -1) {
                console.error(`Page with id ${pageId} not found`);
                return prev;
            }
            const newElements = [...prev];
            const index = newElements[pageIndex].questions.findIndex(
                (el) => el.id === id
            );
            newElements[pageIndex].questions[index] = element;
            return newElements;
        });
    };
    function removePage(id: string) {
        setElements((prev) => {
            return prev.filter((page) => page.id !== id);
        });
    }
    return (
        <DesignerContext.Provider
            value={{
                elements,
                setElements,
                addElement,
                removeElement,
                selectedItem,
                setSelectedItem,
                updateElement,
                openPreview,
                setOpenPreview,
                selectedPageId,
                setSelectedPageId,
                selectedPage,
                setSelectedPage,
                removePage
            }}
        >
            {children}
        </DesignerContext.Provider>
    );
}
