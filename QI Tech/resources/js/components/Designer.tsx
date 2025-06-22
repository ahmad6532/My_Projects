import { useDndMonitor, useDraggable, useDroppable } from "@dnd-kit/core";
import React, { useState } from "react";
import useDesigner from "./hooks/useDesigner";
import {
    ElementsType,
    FormElementInstance,
    FormElements,
} from "./FormElements";
import idGenerator from "./libs/idGenerator";
import { Stack, Typography } from "@mui/material";

const Designer = () => {
    const {
        elements,
        addElement,
        selectedItem,
        setSelectedItem,
        removeElement,
        setSelectedPage,
        removePage,
        setSelectedPageId
    } = useDesigner();
    const [dragStartPageId, setDragStartPageId] = useState<string>("");
    const droppable = useDroppable({
        id: "designer-drop-area",
        data: {
            isDesignerDropArea: true,
        },
    });

    useDndMonitor({
        onDragStart(event) {
            const { active } = event;
            const elementId = active.data.current?.elementId;
            const pageId = elements.find((element) =>
                element.questions.some((question) => question.id === elementId)
            )?.id;
            if (pageId) setDragStartPageId(pageId);
            // const pageIndex = elements.findIndex(element => element.id === pageId);
        },
        onDragEnd(event) {
            const { active, over } = event;
            if (!active || !over) return;

            const isDesignerBtnElement =
                active.data.current?.isDesignerBtnElement;
            const isDesignerDropArea = over.data.current?.isDesignerDropArea;
            if (isDesignerBtnElement && isDesignerDropArea) {
                const type = active.data.current?.type;
                const newElement = FormElements[type as ElementsType].construct(
                    idGenerator(elements)
                );
                addElement(null, elements.length, newElement);
            }

            const isDropTopHalf = over.data.current?.isTopHalfDesignerElement;
            const isDropBottomHalf =
                over.data.current?.isBottomHalfDesignerElement;

            if (isDesignerBtnElement && (isDropTopHalf || isDropBottomHalf)) {
                const type = active.data.current?.type;
                const newElement = FormElements[type as ElementsType].construct(
                    idGenerator(elements)
                );
                const overId = over.data.current?.elementId;
                const pageId = over.data.current?.pageId;
                const pageIndex = elements.findIndex(
                    (element) => element.id === pageId
                );
                const overElementIndex = elements[
                    pageIndex
                ].questions.findIndex((element) => element.id === overId);
                if (overElementIndex == -1) {
                    throw new Error("element not found");
                }

                let indexForNewElement = overElementIndex;
                if (isDropBottomHalf) {
                    indexForNewElement = overElementIndex + 1;
                }
                addElement(pageId, indexForNewElement, newElement);
                return;
            }
            // designer element
            const isDraggingDesignerElement =
                active.data.current?.isDesignerElement;
            if (
                isDraggingDesignerElement &&
                (isDropTopHalf || isDropBottomHalf)
            ) {
                const activeQuestionId = active.data.current?.elementId;
                const dropedOverQuestionId = over.data.current?.elementId;
                const droppedOverPageId = over.data.current?.pageId;
                const pageIndex = elements.findIndex(
                    (element) => element.id === dragStartPageId
                );
                const activeQuestionIndex = elements[
                    pageIndex
                ].questions.findIndex(
                    (element) => element.id === activeQuestionId
                );
                const activeQustion = elements[pageIndex].questions.find(
                    (question) => question.id === activeQuestionId
                )!;
                const droppedOverQuestionIndex = elements[
                    pageIndex
                ].questions.findIndex(
                    (element) => element.id === dropedOverQuestionId
                );
                let indexForNewQuestion = droppedOverQuestionIndex;
                if (isDropBottomHalf) {
                    indexForNewQuestion = droppedOverQuestionIndex + 1;
                }
                removeElement(dragStartPageId, activeQuestionId);
                if (dragStartPageId === droppedOverPageId) {
                    addElement(
                        dragStartPageId,
                        indexForNewQuestion,
                        activeQustion
                    );
                } else {
                    const droppedOverNewPageIndex = elements.findIndex(
                        (element) => element.id === droppedOverPageId
                    );
                    const droppedOverNewQuestionIndex = elements[
                        droppedOverNewPageIndex
                    ].questions.findIndex(
                        (element) => element.id === dropedOverQuestionId
                    );
                    indexForNewQuestion = droppedOverNewQuestionIndex;
                    if (isDropBottomHalf) {
                        indexForNewQuestion = droppedOverNewQuestionIndex + 1;
                    }
                    addElement(
                        droppedOverPageId,
                        indexForNewQuestion,
                        activeQustion
                    );
                }
            }
            // Dropping on Page Placeholder
            const pagePlaceholder = over.data.current?.isPlaceholder;
            if (pagePlaceholder) {
                const placeholderPageId = over.data.current?.pageId;
                const type = active.data.current?.type;
                if (isDraggingDesignerElement) {
                    const startPageIndex = elements.findIndex(
                        (element) => element.id === dragStartPageId
                    );
                    const startElementIndex = elements[
                        startPageIndex
                    ].questions.findIndex(
                        (element) =>
                            element.id === active.data.current?.elementId
                    );
                    const startElement = {
                        ...elements[startPageIndex].questions[
                            startElementIndex
                        ],
                    };
                    const placeholderElements = elements.filter(
                        (element) => element.id === placeholderPageId
                    );
                    removeElement(
                        dragStartPageId as string,
                        active.data.current?.elementId
                    );
                    addElement(
                        placeholderPageId,
                        placeholderElements.length,
                        startElement
                    );
                } else {
                    const newElement = FormElements[
                        type as ElementsType
                    ].construct(idGenerator(elements));
                    addElement(placeholderPageId, elements.length, newElement);
                }
            }
        },
    });

    return (
        <div className="designer-container custom-scroll custom-scroll-form">
            <div className="designer-area">
                <div
                    onClick={() => {
                        if (selectedItem) setSelectedItem(null);
                        setSelectedPage(null);
                    }}
                    className={`drop-area border border-2 border-muted ${
                        droppable.isOver && "border border-2 border-brand "
                    }`}
                    ref={droppable.setNodeRef}
                >
                    {elements.length > 0 && (
                        <div className="d-flex flex-column gap-1">
                            {elements.map((element) => (
                                <Stack
                                    key={element.id}
                                    className="form-page-wrapper"
                                    onClick={(event) => {
                                        setSelectedPage(element);
                                        setSelectedPageId(element.id);
                                        setSelectedItem(null);
                                        event.stopPropagation();
                                    }}
                                >
                                    <Typography variant="h5" fontWeight="bold">
                                        {element.name}
                                    </Typography>
                                    <Typography
                                        variant="body1"
                                        color="#909090"
                                        mb={3}
                                    >
                                        {
                                             element.description
                                            }
                                    </Typography>
                                    {element.questions.length ? (
                                        element.questions.map((question) => (
                                            <div key={question.id}>
                                                <DesignerElementWrapper
                                                    pageId={element.id}
                                                    element={question}
                                                />
                                            </div>
                                        ))
                                    ) : (
                                        <PlaceholderDroppable
                                            pageId={element.id}
                                        />
                                    )}
                                    <div className="page-actions " >
                                        <div></div>
                                        <div className="right-actions">
                                            <button
                                                className="light-btn"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    setSelectedPage(element);
                                                    setSelectedPageId(element.id);
                                                    setSelectedItem(null);
                                                }}
                                            >
                                                <svg
                                                    width="18"
                                                    height="18"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M3 8L15 8M15 8C15 9.65686 16.3431 11 18 11C19.6569 11 21 9.65685 21 8C21 6.34315 19.6569 5 18 5C16.3431 5 15 6.34315 15 8ZM9 16L21 16M9 16C9 17.6569 7.65685 19 6 19C4.34315 19 3 17.6569 3 16C3 14.3431 4.34315 13 6 13C7.65685 13 9 14.3431 9 16Z"
                                                        stroke="black"
                                                        strokeWidth="2"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                    />
                                                </svg>
                                                Settings
                                            </button>
                                            <button
                                                className="light-btn"
                                                onClick={(e) => {
                                                    e.stopPropagation;
                                                    removePage(element.id);
                                                    setSelectedPage(null);
                                                }}
                                            >
                                                <svg
                                                    width="18"
                                                    height="18"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                        stroke="black"
                                                        strokeWidth="2"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                    />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </Stack>
                            ))}
                        </div>
                    )}
                    {droppable.isOver && (
                        <div className="p-1 w-100">
                            <div className="drop-overlay"></div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

function DesignerElementWrapper({
    element,
    pageId,
}: {
    element: FormElementInstance;
    pageId: string;
}) {
    const { setSelectedItem } = useDesigner();
    const topHalf = useDroppable({
        id: element.id + "-top",
        data: {
            type: element.type,
            elementId: element.id,
            isTopHalfDesignerElement: true,
            pageId: pageId,
        },
    });
    const bottomHalf = useDroppable({
        id: element.id + "-bottom",
        data: {
            type: element.type,
            elementId: element.id,
            isBottomHalfDesignerElement: true,
            pageId: pageId,
        },
    });
    const DesignerElement = FormElements[element.type].designerComponent;

    const draggable = useDraggable({
        id: element.id + "-drag-handler",
        data: {
            type: element.type,
            elementId: element.id,
            isDesignerElement: true,
        },
    });

    return (
        <div
            className={`position-relative w-100 h-100 py-1`}
            ref={draggable.setNodeRef}
        >
            <div
                ref={topHalf.setNodeRef}
                className="w-100 h-50 position-absolute"
            ></div>
            <div
                ref={bottomHalf.setNodeRef}
                className="w-100 h-50 position-absolute bottom-0"
            ></div>
            {topHalf.isOver && (
                <div
                    className="position-absolute top-0 w-100 bg-brand rounded-1 "
                    style={{ height: "2px" }}
                ></div>
            )}
            <div
                className={`${
                    draggable.isDragging && "opacity-50 overlay-border rounded"
                }`}
            >
                <DesignerElement
                    pageId={pageId}
                    elementInstance={element}
                    listeners={draggable.listeners}
                    attributes={draggable.attributes}
                />
            </div>
            {bottomHalf.isOver && (
                <div
                    className="position-absolute bottom-0 w-100 bg-brand rounded-1 "
                    style={{ height: "2px" }}
                ></div>
            )}
        </div>
    );
}

function PlaceholderDroppable({ pageId }: { pageId: string }) {
    const droppable = useDroppable({
        id: pageId + "-placeholder",
        data: {
            isPlaceholder: true,
            pageId: pageId,
        },
    });
    return (
        <Stack
            sx={{
                background: droppable.isOver
                    ? "rgba(107, 193, 183, 0.1)"
                    : "transparent",
                opacity: 0.8,
            }}
            ref={droppable.setNodeRef}
            textAlign={"center"}
            border="1px dashed #909090"
            p={4}
        >
            <Typography color="#909090">
                The page is empty. Drag an element from the toolbox
            </Typography>
        </Stack>
    );
}

export default Designer;
