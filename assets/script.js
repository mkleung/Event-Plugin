console.log("hello World");


( function( wp ) {
    wp.richText.registerFormatType(
        'my-custom-format/sample-output', {
            title: 'Sample output',
            tagName: 'samp',
            className: null,
        }
    );
} )( window.wp );



( function( wp ) {
    var MyCustomButton = function( props ) {
        return wp.element.createElement(
            wp.editor.RichTextToolbarButton, {
                icon: 'editor-code',
                title: 'Sample output',
                onClick: function() {
                    console.log( 'toggle format' );
                },
            }
        );
    }
    wp.richText.registerFormatType(
        'my-custom-format/sample-output', {
            title: 'Sample output',
            tagName: 'samp',
            className: null,
            edit: MyCustomButton,
        }
    );
} )( window.wp );


( function( wp ) {
    var withSelect = wp.data.withSelect;
    var ifCondition = wp.compose.ifCondition;
    var compose = wp.compose.compose;
    var MyCustomButton = function( props ) {
        return wp.element.createElement(
            wp.editor.RichTextToolbarButton, {
                icon: 'editor-code',
                title: 'Sample output',
                onClick: function() {
                    console.log( 'toggle format' );
                },
            }
        );
    }
    var ConditionalButton = compose(
        withSelect( function( select ) {
            return {
                selectedBlock: select( 'core/editor' ).getSelectedBlock()
            }
        } ),
        ifCondition( function( props ) {
            return (
                props.selectedBlock &&
                props.selectedBlock.name === 'core/paragraph'
            );
        } )
    )( MyCustomButton );

    wp.richText.registerFormatType(
        'my-custom-format/sample-output', {
            title: 'Sample output',
            tagName: 'samp',
            className: null,
            edit: ConditionalButton,
        }
    );
} )( window.wp );