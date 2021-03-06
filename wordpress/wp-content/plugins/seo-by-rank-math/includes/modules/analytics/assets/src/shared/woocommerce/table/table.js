/**
 * External dependencies
 */
import classnames from 'classnames'
import PropTypes from 'prop-types'
import { find, get, noop, includes, without } from 'lodash'

/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n'
import { Button } from '@wordpress/components'
import { withInstanceId } from '@wordpress/compose'
import { Icon, chevronUp, chevronDown } from '@wordpress/icons'
import { Component, createRef, Fragment } from '@wordpress/element'

/**
 * Internal dependencies
 */
import IndexingDataToggle from '../../../URLInspection/IndexingDataToggle'
import IndexingDataFooter from '../../../URLInspection/IndexingDataFooter'

const ASC = 'asc'
const DESC = 'desc'

const getDisplay = ( cell ) => cell.display || null

/**
 * A table component, without the Card wrapper. This is a basic table display, sortable, but no default filtering.
 *
 * Row data should be passed to the component as a list of arrays, where each array is a row in the table.
 * Headers are passed in separately as an array of objects with column-related properties. For example,
 * this data would render the following table.
 */
class Table extends Component {
	constructor( props ) {
		super( props )
		this.state = {
			tabIndex: null,
			isScrollableRight: false,
			isScrollableLeft: false,
			showIndexingData: [],
		}
		this.container = createRef()
		this.sortBy = this.sortBy.bind( this )
		this.updateTableShadow = this.updateTableShadow.bind( this )
	}

	componentDidMount() {
		const { scrollWidth, clientWidth } = this.container.current
		const scrollable = scrollWidth > clientWidth
		/* eslint-disable react/no-did-mount-set-state */
		this.setState( {
			tabIndex: scrollable ? '0' : null,
		} )
		/* eslint-enable react/no-did-mount-set-state */
		this.updateTableShadow()
		window.addEventListener( 'resize', this.updateTableShadow )
	}

	componentDidUpdate() {
		this.updateTableShadow()
	}

	componentWillUnmount() {
		window.removeEventListener( 'resize', this.updateTableShadow )
	}

	sortBy( key ) {
		const { headers, query } = this.props
		return () => {
			const currentKey =
				query.orderby ||
				get( find( headers, { defaultSort: true } ), 'key', false )
			const currentDir =
				query.order ||
				get(
					find( headers, { key: currentKey } ),
					'defaultOrder',
					DESC
				)
			let dir = DESC
			if ( key === currentKey ) {
				dir = DESC === currentDir ? ASC : DESC
			}
			this.props.onSort( key, dir )
		}
	}

	updateTableShadow() {
		const table = this.container.current
		const { isScrollableRight, isScrollableLeft } = this.state

		const scrolledToEnd =
			table.scrollWidth - table.scrollLeft <= table.offsetWidth
		if ( scrolledToEnd && isScrollableRight ) {
			this.setState( { isScrollableRight: false } )
		} else if ( ! scrolledToEnd && ! this.state.isScrollableRight ) {
			this.setState( { isScrollableRight: true } )
		}

		const scrolledToStart = table.scrollLeft <= 0
		if ( scrolledToStart && isScrollableLeft ) {
			this.setState( { isScrollableLeft: false } )
		} else if ( ! scrolledToStart && ! isScrollableLeft ) {
			this.setState( { isScrollableLeft: true } )
		}
	}

	render() {
		const {
			ariaHidden,
			caption,
			classNames,
			headers,
			instanceId,
			query,
			rowHeader,
			rows,
			indexingData,
		} = this.props

		const { isScrollableRight, isScrollableLeft, tabIndex } = this.state
		const classes = classnames( 'rank-math-table__table', classNames, {
			'is-scrollable-right': isScrollableRight,
			'is-scrollable-left': isScrollableLeft,
		} )
		const sortedBy =
			query.orderby ||
			get( find( headers, { defaultSort: true } ), 'key', false )
		const sortDir =
			query.order ||
			get( find( headers, { key: sortedBy } ), 'defaultOrder', DESC )
		const hasData = !! rows.length

		const captionID = 'caption-' + instanceId
		return (
			<div
				className={ classes }
				ref={ this.container }
				tabIndex={ tabIndex }
				aria-hidden={ ariaHidden }
				aria-labelledby={ captionID }
				role="group"
				onScroll={ this.updateTableShadow }
			>
				<table>
					<caption
						id={ captionID }
						className="rank-math-table__caption screen-reader-text"
					>
						{ caption }
						{ tabIndex === '0' && (
							<small>
								{ __(
									'(scroll to see more)',
									'rank-math'
								) }
							</small>
						) }
					</caption>
					<tbody>
						<tr>
							{ headers.map( ( header, i ) => {
								const {
									cellClassName,
									isLeftAligned,
									isSortable,
									isNumeric,
									key,
									label,
									screenReaderLabel,
									disabled,
								} = header
								if ( disabled ) {
									return false
								}

								const labelId = 'header-' + instanceId + '-' + i
								const thProps = {
									className: classnames(
										'rank-math-table__header',
										cellClassName,
										{
											'is-left-aligned': isLeftAligned,
											'is-sortable': isSortable,
											'is-sorted': sortedBy === key,
											'is-numeric': isNumeric,
										}
									),
								}
								if ( isSortable ) {
									thProps[ 'aria-sort' ] = 'none'
									if ( sortedBy === key ) {
										thProps[ 'aria-sort' ] =
											sortDir === ASC
												? 'ascending'
												: 'descending'
									}
								}
								// We only sort by ascending if the col is already sorted descending
								const iconLabel =
									sortedBy === key && sortDir !== ASC
										? sprintf(
											/* translators: screenReaderLabel */
											__(
												'Sort by %s in ascending order',
												'rank-math'
											),
											screenReaderLabel
										)
										: sprintf(
											/* translators: screenReaderLabel */
											__(
												'Sort by %s in descending order',
												'rank-math'
											),
											screenReaderLabel
										)

								const textLabel = (
									<Fragment>
										<span
											aria-hidden={ Boolean(
												screenReaderLabel
											) }
										>
											{ label }
										</span>
										{ screenReaderLabel && (
											<span className="screen-reader-text">
												{ screenReaderLabel }
											</span>
										) }
									</Fragment>
								)

								return (
									<th
										role="columnheader"
										scope="col"
										key={ i }
										{ ...thProps }
									>
										{ isSortable ? (
											<Fragment>
												<Button
													aria-describedby={ labelId }
													onClick={
														hasData
															? this.sortBy( key )
															: noop
													}
												>
													{ sortedBy === key &&
													sortDir === ASC
														? <Icon icon={ chevronUp } />
														: <Icon icon={ chevronDown } />
													}
													{ textLabel }
												</Button>
												<span
													className="screen-reader-text"
													id={ labelId }
												>
													{ iconLabel }
												</span>
											</Fragment>
										) : (
											textLabel
										) }
									</th>
								)
							} ) }
						</tr>
						{ hasData ? (
							rows.map( ( row, i ) => (
								<Fragment
									key={ i }
								>
									<tr
										onClick={ () => {
											if ( includes( this.state.showIndexingData, i ) ) {
												this.setState( {
													showIndexingData: without( this.state.showIndexingData, i ),
												} )
											} else {
												this.state.showIndexingData.push( i )
												this.setState( {
													showIndexingData: this.state.showIndexingData,
												} )
											}
										} }
									>
										{ row.map( ( cell, j ) => {
											const {
												cellClassName,
												isLeftAligned,
												isNumeric,
											} = headers[ j ]
											const isHeader = rowHeader === j
											const Cell = isHeader ? 'th' : 'td'
											const cellClasses = classnames(
												'rank-math-table__item',
												cellClassName,
												{
													'is-left-aligned': isLeftAligned,
													'is-numeric': isNumeric,
													'is-sorted':
														sortedBy ===
														headers[ j ].key,
												}
											)
											return (
												<Cell
													scope={
														isHeader ? 'row' : null
													}
													rowSpan={ isHeader && ( indexingData && 0 === i ) ? '2' : '' }
													key={ j }
													className={ cellClasses }
												>
													{ getDisplay( cell ) }
												</Cell>
											)
										} ) }
									</tr>
									{
										indexingData && 0 === i &&
										<IndexingDataFooter
											data={ indexingData[ i ] }
											onClick={ () => {
												if ( includes( this.state.showIndexingData, i ) ) {
													this.setState( {
														showIndexingData: without( this.state.showIndexingData, i ),
													} )
												} else {
													this.state.showIndexingData.push( i )
													this.setState( {
														showIndexingData: this.state.showIndexingData,
													} )
												}
											} }
										/>
									}
									{
										indexingData && this.state.showIndexingData.includes( i ) &&
										<IndexingDataToggle data={ indexingData[ i ] } />
									}
								</Fragment>
							) )
						) : (
							<tr>
								<td
									className="rank-math-table__empty-item"
									colSpan={ headers.length }
								>
									<div style={ { lineHeight: '1.3' } }
										dangerouslySetInnerHTML={ {
											__html: sprintf(
												/* translators: general settings */
												__(
													'No data to display. Fetching will be longer for the first time. Please come back after 60 to 90 minutes. If data is still missing, please import the data manually from %s',
													'rank-math'
												),
												'<a href="' + rankMath.adminurl + '?page=rank-math-options-general#setting-panel-analytics"><strong>' + __( "Rank Math > General Settings > Analytics > Click 'Update data manually' button.", 'rank-math' ) + '</strong></a>',
											),
										} }>
									</div>
								</td>
							</tr>
						) }
					</tbody>
				</table>
			</div>
		)
	}
}

Table.propTypes = {
	/**
	 * Controls whether this component is hidden from screen readers. Used by the loading state, before there is data to read.
	 * Don't use this on real tables unless the table data is loaded elsewhere on the page.
	 */
	ariaHidden: PropTypes.bool,
	/**
	 * A label for the content in this table
	 */
	caption: PropTypes.oneOfType( [
		PropTypes.string,
		PropTypes.node,
	] ).isRequired,
	/**
	 * Additional CSS classes.
	 */
	className: PropTypes.string,
	/**
	 * An array of column headers, as objects.
	 */
	headers: PropTypes.arrayOf(
		PropTypes.shape( {
			/**
			 * Boolean, true if this column is the default for sorting. Only one column should have this set.
			 */
			defaultSort: PropTypes.bool,
			/**
			 * String, asc|desc if this column is the default for sorting. Only one column should have this set.
			 */
			defaultOrder: PropTypes.string,
			/**
			 * Boolean, true if this column should be aligned to the left.
			 */
			isLeftAligned: PropTypes.bool,
			/**
			 * Boolean, true if this column is a number value.
			 */
			isNumeric: PropTypes.bool,
			/**
			 * Boolean, true if this column is sortable.
			 */
			isSortable: PropTypes.bool,
			/**
			 * The API parameter name for this column, passed to `orderby` when sorting via API.
			 */
			key: PropTypes.string,
			/**
			 * The display label for this column.
			 */
			label: PropTypes.node,
			/**
			 * Boolean, true if this column should always display in the table (not shown in toggle-able list).
			 */
			required: PropTypes.bool,
			/**
			 * The label used for screen readers for this column.
			 */
			screenReaderLabel: PropTypes.string,
		} )
	),
	/**
	 * A function called when sortable table headers are clicked, gets the `header.key` as argument.
	 */
	onSort: PropTypes.func,
	/**
	 * The query string represented in object form
	 */
	query: PropTypes.object,
	/**
	 * An array of arrays of display/value object pairs.
	 */
	rows: PropTypes.arrayOf(
		PropTypes.arrayOf(
			PropTypes.shape( {
				/**
				 * Display value, used for rendering- strings or elements are best here.
				 */
				display: PropTypes.node,
				/**
				 * "Real" value used for sorting, and should be a string or number. A column with `false` value will not be sortable.
				 */
				value: PropTypes.oneOfType( [
					PropTypes.string,
					PropTypes.number,
					PropTypes.bool,
				] ),
			} )
		)
	).isRequired,
	/**
	 * Which column should be the row header, defaults to the first item (`0`) (but could be set to `1`, if the first col
	 * is checkboxes, for example). Set to false to disable row headers.
	 */
	rowHeader: PropTypes.oneOfType( [ PropTypes.number, PropTypes.bool ] ),
}

Table.defaultProps = {
	ariaHidden: false,
	headers: [],
	onSort: noop,
	query: {},
	rowHeader: 0,
}

export default withInstanceId( Table )
