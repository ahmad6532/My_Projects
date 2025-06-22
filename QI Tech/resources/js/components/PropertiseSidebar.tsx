import React, { ChangeEvent, useEffect, useState } from "react";
import useDesigner from "./hooks/useDesigner";
import { FormElementInstance, FormElements, FormPage } from "./FormElements";
import Select, { SingleValue } from "react-select";
import {
    Stack,
    Typography,
    Accordion,
    AccordionSummary,
    AccordionDetails
} from "@mui/material";
import PagesPropertise from "./PagesPropertise";
import MainBuilder from "./condtionBuilder/MainBuilder";
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';

interface QuestionWithPageId {
    pageId: string;
    question: FormElementInstance;
}

const PropertiseSidebar = () => {
    const {
        selectedItem,
        elements,
        setSelectedItem,
        setSelectedPageId,
        selectedPageId,
        selectedPage,
    } = useDesigner();
    const [sidebarOpen, setIsSidebarOpen] = useState(true);

    const allQuestionsWithPageId: QuestionWithPageId[] = elements.flatMap(
        (page) =>
            page.questions.map((question) => ({
                pageId: page.id,
                question: question,
            }))
    );
    const PropertiseForm = selectedItem
        ? FormElements[selectedItem.type].propertiseComponent
        : null;
    useEffect(() => {
    },[selectedItem,selectedPage])
    return (
        <div
            className="propertise-sidebar custom-scroll"
            style={{position:'fixed',overflowY:'scroll',top:'73px',minHeight:'90vh',maxHeight:'80vh',width: sidebarOpen ? "20%" : "5%"}}
        >
            <nav className="d-flex align-items-center justify-content-between">
                <button
                    className="light-btn custom-light-btn"
                    style={{
                        transform: sidebarOpen
                            ? "rotate(180deg)"
                            : "rotate(0deg)",
                    }}
                    onClick={(e) => {
                        e.stopPropagation();
                        setIsSidebarOpen(!sidebarOpen);
                    }}
                >
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M15 3H16.2C17.8802 3 18.7202 3 19.362 3.32698C19.9265 3.6146 20.3854 4.07354 20.673 4.63803C21 5.27976 21 6.11985 21 7.8V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H15M10 7L15 12M15 12L10 17M15 12L3 12"
                            stroke="black"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </button>
                {sidebarOpen && (
                    <Select
                        className="w-75"
                        onChange={(e) => {
                            if (e) {
                                const el = allQuestionsWithPageId.find(
                                    (item) => item.question.id === e.value
                                );
                                if (el) {
                                    setSelectedItem(el.question);
                                    setSelectedPageId(el.pageId);
                                }
                            }
                        }}
                        styles={{
                            control: (baseStyles, state) => ({
                                ...baseStyles,
                                height: "36px",
                                border: state.isFocused
                                    ? "1px solid #6BC1B7"
                                    : "1px solid #D9D9D9",
                                outline: state.isFocused
                                    ? "1px solid #6BC1B7"
                                    : "1px solid #D9D9D9",
                                boxShadow: state.isFocused
                                    ? "0 0 0 1px #6BC1B7"
                                    : "0 0 0 1px #D9D9D9",
                                "&:hover": {
                                    border: state.isFocused
                                        ? "1px solid #6BC1B7"
                                        : "1px solid #D9D9D9",
                                },
                            }),
                            menu: (baseStyles) => ({
                                ...baseStyles,
                                zIndex: 10, // Increase the z-index value as needed
                            }),
                        }}
                        options={allQuestionsWithPageId.map((element) => ({
                            value: element.question.id,
                            label: element.question.id,
                        }))}
                    ></Select>
                )}
            </nav>
            {
                sidebarOpen && (

                    !selectedItem && !selectedPage ? (
                        <>
                        <Stack mt={6} textAlign='center'>
                        <Typography variant="body2">
                            Please select an element
                        </Typography>
                    </Stack></>
                    ) : (
                        <>
                            {selectedItem === null ? (
                                <>
                                <Accordion defaultExpanded sx={{mt:5,mb:1}}>
                                    <AccordionSummary
                                        expandIcon={<ExpandMoreIcon />}
                                    >
                                        <Typography variant="h6" fontWeight='400'>

                                            General
                                        </Typography>
                                    </AccordionSummary>
                                    <AccordionDetails>
                                        <PagesPropertise />
                                    </AccordionDetails>
                                </Accordion>
                                    <MainBuilder />
                                </>
                            ) : (
                                PropertiseForm && (
                                    <>
                                    <Accordion defaultExpanded sx={{mt:5,mb:1}}>
                                    <AccordionSummary
                                        expandIcon={<ExpandMoreIcon />}
                                    >
                                        <Typography variant="h6" fontWeight='400'>

                                            General
                                        </Typography>
                                    </AccordionSummary>
                                    <AccordionDetails>
                                        <PropertiseForm
                                            pageId={selectedPageId}
                                            elementInstance={selectedItem}
                                        />
                                    </AccordionDetails>
                                </Accordion>
                                        <MainBuilder />
                                    </>
                                )
                            )}
                        </>
                    )
                )
            }
        </div>
    );
};

export default PropertiseSidebar;
