/**
 * Custom Metabox
 * @since 1.0
 **/
import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { __ } from "@wordpress/i18n";
import { useSelect, useDispatch } from "@wordpress/data";
import { CheckboxControl } from "@wordpress/components";

const pluginName = "bn-metabox";
const componentPrefix = "bn";

const BNFeaturedPost = ({ metaField }) => {
	const { postMeta } = useSelect((select) => {
		return {
			postMeta: select("core/editor").getEditedPostAttribute("meta"),
		};
	});
	const { editPost } = useDispatch("core/editor");

	return (
		<PluginDocumentSettingPanel
			name={`${componentPrefix}_plugin`}
			title={__("Featured Post", "breaking-news")}
			className={`${componentPrefix}_container`}
		>
			<CheckboxControl
				label={__("Make this post featured post?", "breaking-news")}
				checked={postMeta[metaField] == "true"}
				onChange={(newValue) => {
					editPost({
						meta: { [metaField]: newValue.toString() },
					});
				}}
			/>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin(pluginName, {
	render() {
		return <BNFeaturedPost metaField="_featured" />;
	},
});
