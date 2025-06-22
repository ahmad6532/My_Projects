import Select, { StylesConfig } from "react-select";
import useDesigner from "../hooks/useDesigner";
import { Condition, QuestionWithPageId } from "./Conditions";
import { Divider, Stack, TextField, Typography } from "@mui/material";
import { useState, useRef } from "react";
import { Editor } from "@tinymce/tinymce-react";
import type { Editor as TinyMCEEditor } from "tinymce";
const style: StylesConfig<any, false> = {
    control: (baseStyles, state) => ({
        ...baseStyles,
        width: "fit-content",
        height: "36px",
        border: "none",
        outline: "none",
        borderRadius: "calc(12.5 * 8px)",
        boxShadow: "none",
        background: state.isDisabled ? "#E6E6E6" : "#F1DBE0",
        cursor: state.isDisabled ? "not-allowed" : "pointer",
        "&:hover": {
            backgroundColor: state.isDisabled ? "#E6E6E6" : "#E60A3E",
        },
        fontSize: "18px",
    }),
    menu: (baseStyles) => ({
        ...baseStyles,
        zIndex: 10,
        width: "250%",
    }),
    indicatorsContainer: (baseStyles) => ({
        ...baseStyles,
        display: "none",
    }),
    option: (baseStyles, state) => ({
        ...baseStyles,
        backgroundColor: state.isDisabled
            ? "transparent"
            : state.isSelected
            ? "#6BC1B7"
            : state.isFocused
            ? "#f0f0f0"
            : undefined,
        color: state.isDisabled
            ? "#a0a0a0"
            : state.isSelected
            ? "#ffffff"
            : "#000000",
        cursor: state.isDisabled ? "not-allowed" : "default",
        "&:hover": {
            backgroundColor:
                !state.isDisabled && state.isFocused ? "#e0e0e0" : undefined,
        },
    }),
    singleValue: (baseStyles, state) => ({
        ...baseStyles,
        color: state.isDisabled ? "#a0a0a0" : "#000000",
    }),
    input: (baseStyles) => ({
        ...baseStyles,
        color: "#000000",
    }),
    placeholder: (baseStyles) => ({
        ...baseStyles,
        color: "#a0a0a0", // Placeholder text color
    }),
};
const styleNormal: StylesConfig<any, false> = {
    control: (baseStyles, state) => ({
        ...baseStyles,
        height: "36px",
        border: state.isFocused ? "1px solid #6BC1B7" : "1px solid #E6E6E6",
        outline: state.isFocused
            ? "1px solid #6BC1B7"
            : "1px solid transparent",
        boxShadow: state.isFocused
            ? "1px solid #6BC1B7"
            : "1px solid transparent",
        cursor: state.isDisabled ? "not-allowed" : "pointer",
        "&:hover": {
            boxShadow: "1px solid #6BC1B7",
        },
    }),
    menu: (baseStyles) => ({
        ...baseStyles,
        zIndex: 10,
        width: "fit-content",
    }),
    indicatorsContainer: (baseStyles) => ({
        ...baseStyles,
        display: "none",
    }),
    option: (baseStyles, state) => ({
        ...baseStyles,
        backgroundColor: state.isDisabled
            ? "transparent"
            : state.isSelected
            ? "#6BC1B7"
            : state.isFocused
            ? "#f0f0f0"
            : undefined,
        color: state.isDisabled
            ? "#a0a0a0"
            : state.isSelected
            ? "#ffffff"
            : "#000000",
        cursor: state.isDisabled ? "not-allowed" : "default",
        "&:hover": {
            backgroundColor:
                !state.isDisabled && state.isFocused ? "#e0e0e0" : undefined,
        },
    }),
};

const CondtionActions = ({
    thenAction,
    conditions,
    setConditions,
}: {
    thenAction: string;
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) => {
    if (!thenAction) return null;
    switch (thenAction) {
        case "Show Page":
            return (
                <ShowPage
                    conditions={conditions}
                    setConditions={setConditions}
                />
            );
        case "Hide Page":
            return (
                <HidePage
                    conditions={conditions}
                    setConditions={setConditions}
                />
            );
        case "Show Question":
            return (
                <ShowQuestion
                    conditions={conditions}
                    setConditions={setConditions}
                />
            );
        case "Hide Question":
            return (
                <HideQuestion
                    conditions={conditions}
                    setConditions={setConditions}
                />
            );
        case "Send Email":
            return (
                <SendEmail
                    conditions={conditions}
                    setConditions={setConditions}
                />
            );
        default:
            return null;
    }
};

export default CondtionActions;

function ShowPage({
    conditions,
    setConditions,
}: {
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) {
    const { elements, selectedPageId } = useDesigner();
    const allPages = elements.flatMap((page) => page.id);
    return (
        <Select
            value={
                conditions[0].showPageId
                    ? {
                          value: conditions[0].showPageId,
                          label: conditions[0].showPageId,
                      }
                    : null
            }
            styles={style}
            classNamePrefix="react-select-custom"
            menuPosition="fixed"
            isOptionDisabled={(opt) => opt.value === selectedPageId}
            options={allPages.map((page) => ({
                value: page,
                label: page,
            }))}
            onChange={(e) => {
                const value = e ? e.value : "";
                setConditions((prevConditions) => {
                    const updatedConditions = [...prevConditions];
                    if (updatedConditions[0]) {
                        updatedConditions[0].showPageId = value;
                        delete updatedConditions[0].hidePageId;
                        delete updatedConditions[0].hideQuestionId;
                        delete updatedConditions[0].showQuestionId;
                    }
                    return updatedConditions;
                });
            }}
        />
    );
}
function HidePage({
    conditions,
    setConditions,
}: {
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) {
    const { elements, selectedPageId } = useDesigner();
    const allPages = elements.flatMap((page) => page.id);
    return (
        <Select
            value={
                conditions[0].hidePageId
                    ? {
                          value: conditions[0].hidePageId,
                          label: conditions[0].hidePageId,
                      }
                    : null
            }
            styles={style}
            classNamePrefix="react-select-custom"
            menuPosition="fixed"
            isOptionDisabled={(opt) => opt.value === selectedPageId}
            options={allPages.map((page) => ({
                value: page,
                label: page,
            }))}
            onChange={(e) => {
                const value = e ? e.value : "";
                setConditions((prevConditions) => {
                    const updatedConditions = [...prevConditions];
                    if (updatedConditions[0]) {
                        updatedConditions[0].hidePageId = value;
                        delete updatedConditions[0].showPageId;
                        delete updatedConditions[0].hideQuestionId;
                        delete updatedConditions[0].showQuestionId;
                    }
                    return updatedConditions;
                });
            }}
        />
    );
}
function ShowQuestion({
    conditions,
    setConditions,
}: {
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) {
    const { elements, selectedPageId, selectedItem } = useDesigner();
    const allQuestionsWithPageId: QuestionWithPageId[] = elements.flatMap(
        (page) =>
            page.questions.map((question) => ({
                pageId: page.id,
                question: question,
            }))
    );
    return (
        <Select
            value={
                conditions[0].showQuestionId
                    ? {
                          value: conditions[0].showQuestionId,
                          label: conditions[0].showQuestionId,
                      }
                    : null
            }
            styles={style}
            classNamePrefix="react-select-custom"
            menuPosition="fixed"
            isOptionDisabled={(opt) => opt.value === selectedItem?.id}
            options={allQuestionsWithPageId.map((element) => ({
                value: element.question.id,
                label: element.question.id,
            }))}
            onChange={(e) => {
                const value = e ? e.value : "";
                setConditions((prevConditions) => {
                    const updatedConditions = [...prevConditions];
                    if (updatedConditions[0]) {
                        updatedConditions[0].showQuestionId = value;
                        delete updatedConditions[0].hideQuestionId;
                        delete updatedConditions[0].showPageId;
                        delete updatedConditions[0].hidePageId;
                    }
                    return updatedConditions;
                });
            }}
        />
    );
}
function HideQuestion({
    conditions,
    setConditions,
}: {
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) {
    const { elements, selectedPageId, selectedItem } = useDesigner();
    const allQuestionsWithPageId: QuestionWithPageId[] = elements.flatMap(
        (page) =>
            page.questions.map((question) => ({
                pageId: page.id,
                question: question,
            }))
    );
    return (
        <Select
            value={
                conditions[0].hideQuestionId
                    ? {
                          value: conditions[0].hideQuestionId,
                          label: conditions[0].hideQuestionId,
                      }
                    : null
            }
            styles={style}
            classNamePrefix="react-select-custom"
            menuPosition="fixed"
            isOptionDisabled={(opt) => opt.value === selectedItem?.id}
            options={allQuestionsWithPageId.map((element) => ({
                value: element.question.id,
                label: element.question.id,
            }))}
            onChange={(e) => {
                const value = e ? e.value : "";
                setConditions((prevConditions) => {
                    const updatedConditions = [...prevConditions];
                    if (updatedConditions[0]) {
                        updatedConditions[0].hideQuestionId = value;
                        delete updatedConditions[0].showQuestionId;
                        delete updatedConditions[0].showPageId;
                        delete updatedConditions[0].hidePageId;
                    }
                    return updatedConditions;
                });
            }}
        />
    );
}
function SendEmail({
    conditions,
    setConditions,
}: {
    conditions: Condition[];
    setConditions: React.Dispatch<React.SetStateAction<Condition[]>>;
}) {
    const { elements, selectedPageId, selectedItem } = useDesigner();
    const [selectedEmailOption, setSelectedEmailOption] = useState("");
    const [content, setContent] = useState("");
    const editorRef = useRef<TinyMCEEditor | null>(null);
    const handleEditorChange = (content: any, editor: any) => {
        setContent(content);
        setConditions((prevConditions) => {
            const updatedConditions = [...prevConditions];
            if (updatedConditions[0]) {
                if (!updatedConditions[0].sendEmail) {
                    updatedConditions[0].sendEmail = {};
                }
            
                updatedConditions[0].sendEmail.content = content;
                delete updatedConditions[0].hideQuestionId;
                delete updatedConditions[0].showQuestionId;
                delete updatedConditions[0].showPageId;
                delete updatedConditions[0].hidePageId;
            }
            
            return updatedConditions;
        });

    };
    const allQuestionsWithPageId: QuestionWithPageId[] = elements.flatMap(
        (page) =>
            page.questions.map((question) => ({
                pageId: page.id,
                question: question,
            }))
    );
    const EmailOptions = [
        { value: "freeTypeEmail", label: "Free Type Email" },
        { value: "ProfileType", label: "Profile Type" },
        { value: "reportedByUser", label: "Reported by user" },
        {
            value: "userSelectedinQuestion",
            label: "User selected in Question X",
        },
    ];
    return (
        <Stack sx={{ width: "100%" }} gap={1} mb={2}>
            <Divider sx={{ my: 1 }} />
            <Typography variant="h5">Send Email</Typography>
            <Select
                styles={styleNormal}
                menuPosition="fixed"
                options={EmailOptions}
                onChange={(e) => {
                    if (e) setSelectedEmailOption(e.value);
                    setConditions((prevConditions) => {
                        const updatedConditions = [...prevConditions];
                        if (updatedConditions[0]) {
                            if (!updatedConditions[0].sendEmail) {
                                updatedConditions[0].sendEmail = {};
                            }
                            updatedConditions[0].sendEmail.emailOption = e.value;
                            delete updatedConditions[0].hideQuestionId;
                            delete updatedConditions[0].showQuestionId;
                            delete updatedConditions[0].showPageId;
                            delete updatedConditions[0].hidePageId;
                        }
                        return updatedConditions;
                    });
                }}
            />
            {selectedEmailOption === "freeTypeEmail" && (
                <TextField
                    type="email"
                    placeholder="Enter Email"
                    size="small"
                    sx={{ background: "white" }}
                />
            )}
            <Editor
                tinymceScriptSrc="/tinymce_react/js/tinymce/tinymce.min.js"
                licenseKey="your-license-key"
                onInit={(_evt, editor) => (editorRef.current = editor)}
                initialValue="<p>This is the initial content of the editor.</p>"
                init={{
                    zIndex: 0,
                    height: 400,
                    menubar: false,
                    plugins: [
                        "advlist",
                        "autolink",
                        "lists",
                        "link",
                        "image",
                        "charmap",
                        "preview",
                        "anchor",
                        "searchreplace",
                        "visualblocks",
                        "code",
                        "fullscreen",
                        "insertdatetime",
                        "media",
                        "table",
                        "code",
                        "help",
                        "wordcount",
                    ],
                    toolbar:
                        "undo redo | blocks | " +
                        "bold italic forecolor | alignleft aligncenter " +
                        "alignright alignjustify | bullist numlist outdent indent | " +
                        "removeformat | help",
                    content_style:
                        "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
                }}
                onEditorChange={handleEditorChange}
            />
        </Stack>
    );
}
