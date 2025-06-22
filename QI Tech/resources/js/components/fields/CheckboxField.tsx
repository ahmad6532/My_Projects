import {
    DraggableAttributes,
    DraggableSyntheticListeners,
} from "@dnd-kit/core";
import {
    ElementsType,
    FormElement,
    FormElementInstance,
    SubmitFunction,
} from "../FormElements";
import useDesigner from "../hooks/useDesigner";
import z from "zod";
import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { ChangeEvent, useEffect, useRef, useState } from "react";
import {
    Alert,
    Box,
    Button,
    Checkbox,
    Collapse,
    Divider,
    FormControl,
    FormControlLabel,
    FormGroup,
    FormLabel,
    IconButton,
    Paper,
    Stack,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    TextField,
    Tooltip,
    Typography,
} from "@mui/material";
import KeyboardArrowDownIcon from "@mui/icons-material/KeyboardArrowDown";
import KeyboardArrowUpIcon from "@mui/icons-material/KeyboardArrowUp";
import DeleteIcon from "@mui/icons-material/Delete";
import AddCircleOutlineIcon from "@mui/icons-material/AddCircleOutline";
import { optionCSS } from "react-select/dist/declarations/src/components/Option";
const type: ElementsType = "CheckboxField";

const extraAttributes = {
    label: "Checkbox",
    helperText: "Helper text",
    required: false,
    options: [{ label: "item1", value: "item1", description: "" }],
};
type Option = { label: string; value: string; description?: string };

const propertiseSchema = z.object({
    label: z.string().min(5).max(30),
    helperText: z.string().max(1000),
    required: z.boolean().default(false),
    id: z.string().min(1).max(200),
    options: z.array(
        z.object({
            label: z.string().min(1).max(200),
            value: z.string().min(1).max(200),
            description: z.string().max(1000).optional(),
        })
    ),
});

export const CheckboxFieldFormElement: FormElement = {
    type,

    construct: (id: string) => ({
        id,
        type,
        extraAttributes,
    }),
    designerBtnElement: {
        icon: (
            <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M7.5 12L10.5 15L16.5 9M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z"
                    stroke="black"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                />
            </svg>
        ),
        label: "Checkbox",
    },
    designerComponent: DesignerComponent,
    formComponent: FormComponent,
    propertiseComponent: PropertiseComponent,
    validate: (
        formElement: FormElementInstance,
        currentValue: string
    ): boolean => {
        const element = formElement as CustomInstance;
        if (element.extraAttributes.required) {
            return currentValue.length > 0;
        }
        return true;
    },
};

type CustomInstance = FormElementInstance & {
    extraAttributes: {
        label: string;
        helperText: string;
        required: boolean;
        options: Option[];
    };
};

function DesignerComponent({
    pageId,
    elementInstance,
    listeners,
    attributes,
}: {
    pageId: string;
    elementInstance: FormElementInstance;
    listeners?: DraggableSyntheticListeners;
    attributes?: DraggableAttributes;
}) {
    const {
        removeElement,
        setSelectedItem,
        updateElement,
        setSelectedPage,
        setSelectedPageId,
    } = useDesigner();
    const element = elementInstance as CustomInstance;
    const { label, helperText, required, options } = element.extraAttributes;

    function updateRequired() {
        updateElement(pageId, element.id, {
            ...element,
            extraAttributes: {
                ...element.extraAttributes,
                required: !element.extraAttributes.required,
            },
        });
    }
    const [openIndex,setOpenIndex] = useState<number | null>(null)
    return (
        <div
            className="main-question-wrapper"
            key={element.id}
            onClick={(e) => {
                e.stopPropagation();
                setSelectedPageId(pageId);
                setSelectedPage(null);
                setSelectedItem(element);
            }}
        >
            <div className="drag-area" {...listeners} {...attributes}>
                <svg viewBox="0 0 20 20">
                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                </svg>
            </div>
            <div className="input-area">
                <div style={{ paddingBottom: "16px" }}>
                    <h5 className="m-0 p-0">
                        {label} {required && "*"}
                    </h5>
                    {helperText && (
                        <p className="m-0 desc mt-1">{helperText}</p>
                    )}
                </div>
                <FormGroup>
                    {options.map((option,index) => (
                        <>
                        <FormControlLabel
                            key={option.value}
                            control={
                                <Checkbox
                                    // checked={selectedValues.includes(option.value)}
                                    // onChange={handleChange}
                                    // onBlur={handleBlur}
                                    name={option.value}
                                />
                            }
                            label={option.label}
                            />
                            {option.description && (

                                <Stack ml={10}>
                                
                                <Button sx={{textAlign:"left",width:'fit-content',padding:0,fontSize:'12px'}} onClick={()=>setOpenIndex(prev=> prev===index ? null : index)}>Click for example</Button>
                                <Collapse in={openIndex === index} >
                                    <Typography variant="body1" color="text.secondary" my={2} width={'80%'}>
                                        {option.description}
                                    </Typography>
                                </Collapse>
                                </Stack>
                            )
                            }
                            </>
                    ))}
                </FormGroup>
            </div>

            <div className="question-actions">
                <div></div>
                <div className="right-actions">
                    <button
                        className="light-btn"
                        onClick={(e) => {
                            e.stopPropagation();
                            setSelectedItem(element);
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
                            updateRequired();
                        }}
                        style={{
                            background: required
                                ? "rgba(0,0,0,0.03)"
                                : "transparent",
                        }}
                    >
                        <svg
                            className="required-icon"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            width="13"
                            height="13"
                            viewBox="0 0 256 256"
                        >
                            <path
                                fill="#000000"
                                d="M223,160.1L167.3,128L223,95.9c9.4-5.4,12.6-17.5,7.2-26.9c-5.4-9.4-17.5-12.6-26.9-7.2l-55.6,32.1V29.7c0-10.9-8.8-19.7-19.7-19.7c-10.9,0-19.7,8.8-19.7,19.7v64.3L52.7,61.8c-9.4-5.4-21.4-2.2-26.9,7.2c-5.4,9.4-2.2,21.4,7.2,26.9L88.7,128L33,160.1c-9.4,5.4-12.6,17.5-7.2,26.9c5.4,9.4,17.5,12.6,26.9,7.2l55.6-32.1v64.3c0,10.9,8.8,19.7,19.7,19.7c10.9,0,19.7-8.8,19.7-19.7V162l55.6,32.1c9.4,5.4,21.4,2.2,26.9-7.2C235.6,177.6,232.4,165.6,223,160.1z"
                            />
                        </svg>
                        Required
                    </button>
                    <button
                        className="light-btn"
                        onClick={(e) => {
                            e.stopPropagation;
                            removeElement(pageId, element.id);
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
        </div>
    );
}
function FormComponent({
    elementInstance,
    submitValue,
    isInvalid,
    defaultValue = [],
}: {
    elementInstance: FormElementInstance;
    submitValue?: SubmitFunction;
    isInvalid?: boolean;
    defaultValue?: any;
}) {
    const { removeElement, setSelectedItem } = useDesigner();
    const [selectedValues, setSelectedValues] =
        useState<string[]>(defaultValue);
    const [error, setError] = useState(false);

    useEffect(() => {
        setError(isInvalid === true);
    }, [isInvalid]);

    const element = elementInstance as CustomInstance;
    const { label, helperText, required, options } = element.extraAttributes;
    const [openIndex,setOpenIndex] = useState<number|null>(null);

    const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
        const { name, checked } = event.target;
        setSelectedValues((prev) =>
            checked ? [...prev, name] : prev.filter((value) => value !== name)
        );
    };

    const handleBlur = () => {
        if (!submitValue) return;
        const valid = CheckboxFieldFormElement.validate(
            element,
            JSON.stringify(selectedValues)
        );
        setError(!valid);
        if (!valid) return;
        submitValue(element.id, JSON.stringify(selectedValues));
    };

    return (
        <div className="main-question-wrapper prev">
            <div className="input-area">
                <div style={{ paddingBottom: "16px" }}>
                    <h5 className="m-0 p-0">
                        {label} {required && "*"}
                    </h5>
                    {helperText && (
                        <p className="m-0 desc mt-1">{helperText}</p>
                    )}
                </div>
                <FormControl component="fieldset">
                    <FormGroup>
                        {options.map((option,index) => (
                            <>
                            <FormControlLabel
                                key={option.value}
                                control={
                                    <Checkbox
                                        checked={selectedValues.includes(
                                            option.value
                                        )}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        name={option.value}
                                    />
                                }
                                label={option.label}
                            />
                            {option.description &&
                            <Stack marginLeft={6}>
                            
                            <Button sx={{textAlign:"left",width:'fit-content',padding:0,fontSize:'12px'}} onClick={()=>setOpenIndex(prev=> prev===index ? null : index)}>Click for example</Button>
                                <Collapse in={openIndex === index} >
                                    <Typography variant="body1" color="text.secondary" my={2} width={'80%'}>
                                        {option.description}
                                    </Typography>
                                </Collapse>
                            </Stack>
                            }
                            </>
                        ))}
                    </FormGroup>
                </FormControl>
            </div>
            {error && (
                <Alert
                    sx={{ width: "94%", position: "absolute", bottom: "5px" }}
                    severity="error"
                >
                    Response is required
                </Alert>
            )}
        </div>
    );
}

type propertiseFormSchemaType = z.infer<typeof propertiseSchema>;
function PropertiseComponent({
    elementInstance,
}: {
    elementInstance: FormElementInstance;
}) {
    const [renderKey,setRenderKey] = useState(new Date().getTime())
    const element = elementInstance as CustomInstance;
    const {
        control,
        handleSubmit,
        formState: { errors },
        getValues,
        reset,
        setError,
        watch,
        setValue,
    } = useForm<propertiseFormSchemaType>({
        resolver: zodResolver(propertiseSchema),
        mode: "onChange",
        defaultValues: {
            label: element.extraAttributes.label,
            helperText: element.extraAttributes.helperText,
            required: element.extraAttributes.required,
            id: element.id,
            options: element.extraAttributes.options,
        },
    });
    

    const { updateElement, elements, setSelectedItem, selectedPageId } =
        useDesigner();
    const pageIndex = elements.findIndex((page) => page.id == selectedPageId);
    const found = elements[pageIndex].questions.find(
        (e) => e.id === element.id
    );
    useEffect(() => {
        if (found) {
            reset({
                id: found.id,
                label: found.extraAttributes?.label,
                helperText: found.extraAttributes?.helperText,
                required: found.extraAttributes?.required,
                options: found.extraAttributes?.options,
            });
        }
    }, [found, reset]);

    function applyChanges(values: propertiseFormSchemaType) {
        const { label, helperText, required, options } = values;
        updateElement(selectedPageId, element.id, {
            ...element,
            extraAttributes: {
                label,
                helperText,
                required,
                options,
            },
        });
    }
    const isIdUnique = (id: string) => {
        const allQuestions = elements.flatMap((page) => page.questions);
        return !allQuestions.some((el) => el.id === id);
    };
    function idchange(value: string) {
        if (isIdUnique(value)) {
            updateElement(selectedPageId, element.id, {
                ...element,
                id: value,
            });
            setSelectedItem(element);
            return;
        } else {
            setError("id", {
                type: "manual",
                message: "ID must be unique",
            });
        }
    }
    const [open, setOpen] = useState<number | null>(null);
    return (
        <>
            <Typography variant="body1" color="GrayText">
                General
            </Typography>
            <Divider
                orientation="horizontal"
                sx={{ marginBottom: 3, borderColor: "rgba(0,0,0,0.5)" }}
            />
            <Stack direction={"row"} spacing={1} alignItems={"center"} mb={2}>
                <Controller
                    name="id"
                    shouldUnregister
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            onBlur={(e) => idchange(e.target.value)}
                            error={!!errors.id}
                            helperText={errors.id ? errors.id.message : ""}
                            label="Question ID"
                            fullWidth
                            variant="outlined"
                            size="small"
                            multiline
                        />
                    )}
                />
                <Tooltip title="A Question ID that is not visible to users">
                    <IconButton>
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                stroke="#72C4BA"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </IconButton>
                </Tooltip>
            </Stack>
            <form
                onChange={handleSubmit(applyChanges)}
                className="mt-4 mx-auto w-100"
            >
                <Controller
                    name="label"
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            error={!!errors.label}
                            helperText={
                                errors.label ? "Please enter a valid value" : ""
                            }
                            label="Label"
                            fullWidth
                            variant="outlined"
                            size="small"
                        />
                    )}
                />

                <Controller
                    name="helperText"
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            error={!!errors.helperText}
                            multiline
                            minRows={2}
                            maxRows={4}
                            helperText={
                                errors.helperText
                                    ? "Please enter a valid value"
                                    : ""
                            }
                            label="Description"
                            fullWidth
                            sx={{ marginY: 2 }}
                            variant="outlined"
                            size="small"
                        />
                    )}
                />

                <TableContainer component={Paper} elevation={0}>
                    <Table aria-label="collapsible table" size="small">
                        <TableHead>
                            <TableRow className="header-cell">
                                <TableCell
                                    sx={{ paddingY: 0, color: "#909090" }}
                                >
                                    Value
                                </TableCell>
                                <TableCell
                                    sx={{ paddingY: 0, color: "#909090" }}
                                >
                                    Text
                                </TableCell>
                                <TableCell
                                    sx={{ paddingY: 0, color: "#909090" }}
                                >
                                    <Controller
                                        name="options"
                                        control={control}
                                        render={({ field }) => (
                                            <IconButton
                                                onClick={(e) => {
                                                    e.preventDefault();
                                                    setValue(
                                                        "options",
                                                        field.value.concat({
                                                            label: `item${
                                                                field.value
                                                                    .length + 1
                                                            }`,
                                                            value: `item${
                                                                field.value
                                                                    .length + 1
                                                            }`,
                                                        })
                                                    );
                                                    updateElement(selectedPageId, element.id, {
                                                        ...element,
                                                        extraAttributes: {
                                                            ...getValues(),
                                                            options:getValues("options")
                                                        },
                                                    });
                                                }}
                                            >
                                                <AddCircleOutlineIcon />
                                            </IconButton>
                                        )}
                                    />{" "}
                                </TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody >
                            {watch("options")?.map((row, index) => (
                                <Controller control={control} name="options" key={index} render={({ field:mainField }) => (
                                    <>
                                        <TableRow
                                            sx={{
                                                "& > *": { borderBottom: "unset" },
                                                paddingY:0
                                            }}
                                        >
                                            <TableCell
                                                component="th"
                                                scope="row"
                                                size="small"
                                                sx={{ paddingY: 0 }}
                                            >
                                                
                                                        <Controller
                                                    name={`options.${index}.value`}
                                                    control={control}
                                                    render={({ field }) => (
                                                        <TextField
                                                            {...field}
                                                            error={
                                                                !!errors.options?.[
                                                                    index
                                                                ]?.value
                                                            }
                                                            helperText={
                                                                errors.options?.[
                                                                    index
                                                                ]?.value
                                                                    ? "Please enter a valid value"
                                                                    : ""
                                                            }
                                                            fullWidth
                                                            sx={{
                                                                marginY: 2,
                                                                padding: 0,
                                                            }}
                                                            variant="outlined"
                                                            size="small"
                                                        />
                                                    )}
                                                />
                                            </TableCell>
                                            <TableCell
                                                component="th"
                                                scope="row"
                                                size="small"
                                                sx={{ paddingY: 0 }}
                                            >
                                                <Controller
                                                    name={`options.${index}.label`}
                                                    control={control}
                                                    render={({ field }) => (
                                                        <TextField
                                                            {...field}
                                                            error={
                                                                !!errors.options?.[
                                                                    index
                                                                ]?.label
                                                            }
                                                            helperText={
                                                                errors.options?.[
                                                                    index
                                                                ]?.label
                                                                    ? "Please enter a valid value"
                                                                    : ""
                                                            }
                                                            fullWidth
                                                            sx={{
                                                                marginY: 2,
                                                                padding: 0,
                                                            }}
                                                            variant="outlined"
                                                            size="small"
                                                        />
                                                    )}
                                                />
                                            </TableCell>
                                            <TableCell
                                                size="small"
                                                sx={{ padding: 0 }}
                                            >
                                                <FormGroup
                                                    sx={{
                                                        display: "flex",
                                                        flexDirection: "row",
                                                    }}
                                                >
                                                    <IconButton
                                                        aria-label="expand row"
                                                        size="small"
                                                        onClick={() =>
                                                            setOpen(prev => prev === index ? -1 : index)
                                                        }
                                                    >
                                                        {open == index ? (
                                                            <KeyboardArrowUpIcon />
                                                        ) : (
                                                            <KeyboardArrowDownIcon />
                                                        )}
                                                    </IconButton>
                                                            <IconButton
                                                                aria-label="expand row"
                                                                size="small"
                                                                onClick={(e) => {
                                                                    e.preventDefault();
                                                                    const newOptions = [...mainField.value];
                                                                    newOptions.splice(index, 1);
                                                                    setValue('options', newOptions);
                                                                    updateElement(selectedPageId, element.id, {
                                                                        ...element,
                                                                        extraAttributes: {
                                                                            ...getValues(),
                                                                            options:newOptions
                                                                        },
                                                                    });
                                                                    setRenderKey(new Date().getTime());
                                                                }}
                                                            >
                                                                <DeleteIcon />
                                                            </IconButton>
                                                </FormGroup>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell
                                                style={{
                                                    paddingBottom: 0,
                                                    paddingTop: 0,
                                                }}
                                                colSpan={6}
                                            >
                                                <Collapse
                                                    in={open === index}
                                                    timeout="auto"
                                                    unmountOnExit
                                                >
                                                    <Box sx={{ margin: 1 }}>
                                                    <Controller
                                                    name={`options.${index}.description`}
                                                    control={control}
                                                    render={({ field }) => (
                                                        <TextField
                                                            {...field}
                                                            error={
                                                                !!errors.options?.[
                                                                    index
                                                                ]?.description
                                                            }
                                                            helperText={
                                                                errors.options?.[
                                                                    index
                                                                ]?.description
                                                                    ? "Please enter a valid value"
                                                                    : ""
                                                            }
                                                            fullWidth
                                                            multiline
                                                            label="Helper Text"
                                                            sx={{
                                                                marginY: 2,
                                                                padding: 0,
                                                            }}
                                                            variant="outlined"
                                                            size="small"
                                                        />
                                                    )}
                                                />
                                                    </Box>
                                                </Collapse>
                                            </TableCell>
                                        </TableRow>
                                    </>
                                )} />
                            ))}
                            {/* {rows.map((row) => (
                        <Row key={row.name} row={row} />
                    ))} */}
                        </TableBody>
                    </Table>
                </TableContainer>
            </form>
            <Stack
                direction="row"
                spacing={1}
                sx={{ marginTop: 1 }}
                alignItems="center"
            >
                <Controller
                    name="required"
                    control={control}
                    render={({ field }) => (
                        <Checkbox
                            {...field}
                            checked={field.value}
                            onChange={(e) => {
                                field.onChange(e.target.checked);
                                applyChanges({
                                    ...getValues(),
                                    required: e.target.checked,
                                });
                            }}
                        />
                    )}
                />
                <Typography>Required</Typography>
            </Stack>
        </>
    );
}
