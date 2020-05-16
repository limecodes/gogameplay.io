export default (asset) => {
    const assetBase = process.env.MIX_ASSET_URL;

    return `${assetBase}${asset}`;
}
